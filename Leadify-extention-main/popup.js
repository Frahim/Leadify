// popup.js

// DOM elements
const loginView = document.getElementById("loginView");
const mainView = document.getElementById("mainView");
const emailInput = document.getElementById("email");
const passwordInput = document.getElementById("password");
const loginButton = document.getElementById("loginButton");
const logoutButton = document.getElementById("logoutButton");
const importButton = document.getElementById("importButton");
const copyButton = document.getElementById("copyButton");
const loginMessage = document.getElementById("loginMessage");
const actionMessage = document.getElementById("actionMessage");
const userEmailDisplay = document.getElementById("userEmailDisplay");

document.addEventListener("DOMContentLoaded", () => {
chrome.storage.local.get(["isLoggedIn", "token", "userEmail"], (result) => {
        if (result.isLoggedIn && result.token) {
            showLoggedInState(result.userEmail);
        } else {
            showLoggedOutState();
        }

        // Always check if it's a LinkedIn page to precheck
        chrome.tabs.query({ active: true, currentWindow: true }, (tabs) => {
            if (tabs.length === 0) return;
            const tabId = tabs[0].id;
            const tabUrl = tabs[0].url;

            if (/https:\/\/www\.linkedin\.com\/in\//.test(tabUrl)) {
                chrome.scripting.executeScript({
                    target: { tabId },
                    files: ["content.js"],
                }, () => {
                    if (chrome.runtime.lastError) {
                        console.warn("Script injection failed:", chrome.runtime.lastError.message);
                        return;
                    }

                    chrome.tabs.sendMessage(tabId, {
                        action: "scrapeLinkedInProfile",
                        context: "precheck",
                    });
                });
            }
        });
    });

    chrome.tabs.query({ active: true, currentWindow: true }, (tabs) => {
    if (tabs.length === 0) return;
    const tabId = tabs[0].id;
    const tabUrl = tabs[0].url;

    // Check if it's a LinkedIn profile page
    if (/https:\/\/www\.linkedin\.com\/in\//.test(tabUrl)) {
        chrome.scripting.executeScript(
            {
                target: { tabId },
                files: ["content.js"],
            },
            () => {
                if (chrome.runtime.lastError) {
                    console.warn("Could not inject script:", chrome.runtime.lastError.message);
                    return;
                }
                chrome.tabs.sendMessage(tabId, { action: "scrapeLinkedInProfile", context: "precheck" });
            }
        );
    }
});

});

// Show the state when the user is logged in
function showLoggedInState(email) {
    loginView.classList.add("hidden");
    mainView.classList.remove("hidden");
    userEmailDisplay.textContent = email || "Not available";
    loginMessage.textContent = "";
    actionMessage.className = "message";
    actionMessage.textContent = "";
    importButton.disabled = false;
}

// Show the state when the user is logged out
function showLoggedOutState() {
    mainView.classList.add("hidden");
    loginView.classList.remove("hidden");
    emailInput.value = "";
    passwordInput.value = "";
    loginMessage.className = "message info";
    loginMessage.textContent = "Please log in to continue.";
    actionMessage.textContent = "";
    importButton.disabled = false;
}

loginButton.addEventListener("click", async () => {
    const email = emailInput.value;
    const password = passwordInput.value;
    loginMessage.className = "message info";
    loginMessage.textContent = "Attempting to log in...";

    try {
        const response = await fetch("http://127.0.0.1:8000/api/login", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ email, password }),
        });

        const data = await response.json();

        if (response.ok) {
            chrome.storage.local.set({ token: data.token, isLoggedIn: true, userEmail: email }, () => {
                loginMessage.className = "message success";
                loginMessage.textContent = "Login successful!";
                showLoggedInState(email);
            });
        } else {
            loginMessage.className = "message error";
            loginMessage.textContent = `Login failed: ${data.message || 'Unknown error'}`;
        }
    } catch (error) {
        loginMessage.className = "message error";
        loginMessage.textContent = "An error occurred during login.";
    }
});

logoutButton.addEventListener("click", async () => {
    try {
        await chrome.storage.local.remove(['token', 'isLoggedIn', 'userEmail']);
        showLoggedOutState();
        loginMessage.className = "message success";
        loginMessage.textContent = "Logged out successfully!";
    } catch (error) {
        loginMessage.className = "message error";
        loginMessage.textContent = "An error occurred during logout.";
    }
});

let isImporting = false;

importButton.addEventListener("click", () => {
    chrome.tabs.query({ active: true, currentWindow: true }, (tabs) => {
        if (!tabs.length) return;

        const tabId = tabs[0].id;

        chrome.scripting.executeScript(
            {
                target: { tabId },
                files: ["content.js"],
            },
            () => {
                if (chrome.runtime.lastError) {
                    actionMessage.className = "message error";
                    actionMessage.textContent = "Failed to inject content script.";
                    return;
                }
                chrome.tabs.sendMessage(tabId, {
                    action: "scrapeLinkedInProfile",
                    context: "import",
                });
                actionMessage.className = "message info";
                actionMessage.textContent = "Scraping profile data for import...";
            }
        );
    });
});

copyButton.addEventListener("click", () => {
    chrome.tabs.query({ active: true, currentWindow: true }, (tabs) => {
        if (!tabs.length) return;

        const tabId = tabs[0].id;

        chrome.scripting.executeScript(
            {
                target: { tabId },
                files: ["content.js"],
            },
            () => {
                if (chrome.runtime.lastError) {
                    actionMessage.className = "message error";
                    actionMessage.textContent = "Failed to inject content script.";
                    return;
                }

                chrome.tabs.sendMessage(tabId, { action: "scrapeLinkedInProfile" }, (response) => {
                    if (!response || !response.success) {
                        actionMessage.className = "message error";
                        actionMessage.textContent = "Failed to scrape profile data!";
                        return;
                    }

                    const profileData = response.profileData;
                    const tsvData = `${profileData.name || ''}\t${profileData.designation || ''}\t${profileData.company || ''}\t${profileData.location || ''}\t\t\t\t${profileData.url || ''}\t${profileData.copyTime || ''}`;

                    chrome.scripting.executeScript({
                        target: { tabId },
                        func: (dataToCopy) => {
                            const tempInput = document.createElement('textarea');
                            tempInput.value = dataToCopy;
                            tempInput.setAttribute('readonly', '');
                            tempInput.style.position = 'absolute';
                            tempInput.style.left = '-9999px';
                            document.body.appendChild(tempInput);
                            tempInput.select();
                            document.execCommand('copy');
                            document.body.removeChild(tempInput);
                            chrome.runtime.sendMessage({ action: 'copySuccess' });
                        },
                        args: [tsvData],
                    }).catch(() => {
                        actionMessage.className = "message error";
                        actionMessage.textContent = 'Failed to copy data to clipboard!';
                    });
                });
            }
        );
    });
});

// Handle messages from content script
chrome.runtime.onMessage.addListener((request, sender, sendResponse) => {
    if ((request.action === "sendProfileDataForImport" || request.action === "sendProfileDataForPrecheck") && !isImporting) {
        const { name, designation, company, location, url } = request.profileData;
        const leads = [{ name, designation, company, location, url }];

        chrome.storage.local.get(["token"], async (result) => {
            const token = result.token;

            if (!token) return;

            const alreadyExists = await isLeadAlreadyImported(token, url);

            if (alreadyExists) {
                importButton.disabled = true;
                document.getElementById("disabledNote").style.display = "block";

                if (request.action === "sendProfileDataForImport") {
                    actionMessage.className = "message info";
                    actionMessage.textContent = "This lead is already imported.";
                }

                return;
            }

            // It's a new lead and user clicked import
            if (request.action === "sendProfileDataForImport") {
                importButton.disabled = false;
                document.getElementById("disabledNote").style.display = "none";
                await importLeads(token, leads, actionMessage);
            }
        });
    }

    // Handle copy success/fail messages as before
});



async function importLeads(token, leads, messageElement) {
    messageElement.className = "message info";
    messageElement.textContent = "Checking for existing leads...";
    const existingLeads = await fetchExistingLeads(token, messageElement);
    if (!existingLeads) return;

    const existingUrls = existingLeads.map(l => l.url?.trim().toLowerCase());
    const newLeads = leads.filter(lead => !existingUrls.includes(lead.url?.trim().toLowerCase()));

    if (newLeads.length === 0) {
        messageElement.className = "message info";
        messageElement.textContent = "No new leads to import.";
        return;
    }

    messageElement.className = "message info";
    messageElement.textContent = `Importing ${newLeads.length} new lead(s)...`;

    try {
        const response = await fetch("http://127.0.0.1:8000/api/leads/import", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                Authorization: `Bearer ${token}`,
            },
            body: JSON.stringify({ leads: newLeads }),
        });

        const data = await response.json();
        if (!response.ok) throw new Error(data.message || "Import failed");

        messageElement.className = "message success";
        messageElement.textContent = data.message || "Leads imported successfully!";
    } catch (error) {
        messageElement.className = "message error";
        messageElement.textContent = `Import failed: ${error.message}`;
    }
}

async function fetchExistingLeads(token, messageElement) {
    try {
        const response = await fetch("http://127.0.0.1:8000/api/leads", {
            headers: {
                Authorization: `Bearer ${token}`,
                Accept: "application/json",
            },
        });

        if (!response.ok) {
            const errorData = await response.json();
            messageElement.className = "message error";
            messageElement.textContent = errorData.message || "Failed to fetch leads";
            return null;
        }

        return await response.json();
    } catch (error) {
        messageElement.className = "message error";
        messageElement.textContent = "Error fetching leads.";
        return null;
    }
}

async function isLeadAlreadyImported(token, profileUrl) {
    try {
        const leads = await fetchExistingLeads(token, actionMessage);
        const existingUrls = leads.map(l => l.url?.trim().toLowerCase()).filter(Boolean);
        return existingUrls.includes(profileUrl?.trim().toLowerCase());
    } catch {
        return false;
    }
}
