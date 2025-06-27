// content.js

(() => {
  // ✅ Prevent duplicate injection
  if (window.__leadifyContentInjected) {
    console.log("content.js: Already injected.");
    return;
  }
  window.__leadifyContentInjected = true;

  // ✅ Optional throttle to avoid rapid scraping
let lastScrapeTime = 0;

chrome.runtime.onMessage.addListener((request, sender, sendResponse) => {
  // ✅ Allow popup to check if we're already injected
  if (request.action === "ping") {
    sendResponse({ injected: true });
    return;
  }

  // ✅ Handle scrape command
  if (request.action === "scrapeLinkedInProfile") {
    const now = Date.now();
    if (now - lastScrapeTime < 2000) {
      console.log("content.js: Scrape throttled.");
      return;
    }
    lastScrapeTime = now;

    try {
      // Scrape data
      const nameElement = document.querySelector('h1.v-align-middle.break-words');
      const headlineElement = document.querySelector('.text-body-medium.break-words');

      let company = '';
      let location = '';

      const experienceSection = document.querySelector('#experience');
      const firstJob = experienceSection?.querySelector('ul > li');

      if (firstJob) {
        const companyElement = firstJob.querySelector('span[aria-hidden="true"]');
        const locationElement = firstJob.querySelector('.t-14.t-normal.t-black--light');

        company = companyElement ? companyElement.textContent.trim() : '';
        location = locationElement ? locationElement.textContent.trim() : '';
      } else {
        const companyFallback = document.querySelector('div[style*="-webkit-line-clamp:2"]');
        const locationFallback = document.querySelector('.text-body-small.inline.t-black--light.break-words');

        company = companyFallback ? companyFallback.textContent.trim() : '';
        location = locationFallback ? locationFallback.textContent.trim() : '';
      }

      const url = window.location.href;
      const copyTime = new Date().toLocaleString();

      const profileData = {
        name: nameElement?.textContent.trim() || '',
        designation: headlineElement?.textContent.trim() || '',
        company,
        location,
        url,
        copyTime,
      };

      const hasData = Object.values(profileData).some(val => val !== '');
      if (!hasData) {
        console.warn("content.js: No valid data found.");
        sendResponse?.({ success: false, error: 'No data extracted.' });
        return;
      }

      console.log("content.js: Scraped Data:", profileData);

      // ✅ Respond to context
      switch (request.context) {
        case 'copy':
          chrome.runtime.sendMessage({ action: "sendProfileDataForCopy", profileData });
          break;
        case 'import':
          chrome.runtime.sendMessage({ action: "sendProfileDataForImport", profileData });
          break;
        case 'precheck':
          chrome.runtime.sendMessage({ action: "sendProfileDataForPrecheck", profileData });
          break;
        default:
          sendResponse?.({ success: true, profileData }); // fallback
      }
    } catch (err) {
      console.error("content.js: Scraping error:", err);
      sendResponse?.({ success: false, error: err.message });
    }
  }
});


})();


