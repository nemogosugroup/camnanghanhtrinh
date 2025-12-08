import { PublicClientApplication } from "@azure/msal-browser";

const msalConfig = {
    auth: {
        clientId: "6746f92c-91cd-4625-9e9f-ffeafb3d26bb",
        authority: "https://login.microsoftonline.com/common",
        redirectUri: window.location.origin + "/auth/callback",
    }
};

export const msalInstance = new PublicClientApplication(msalConfig);
