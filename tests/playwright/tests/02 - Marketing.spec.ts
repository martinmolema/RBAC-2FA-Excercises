import {expect, Page, test} from "@playwright/test";
import {URL_MARKETING_INTRANET} from "./lib/urls";
import {
    USER_MARKETING_MANAGER,
    USER_MARKETING_NORMAL,
} from "./lib/TestUserInfo";
import {gotoWebsiteAndTestNavigationForUser} from "./lib/NavigationTestSupport";



test('Marketing medewerker', async ({page}) => {

    const user = USER_MARKETING_NORMAL;
    await gotoWebsiteAndTestNavigationForUser(page, URL_MARKETING_INTRANET, user);
});

test('Marketing manager', async ({page}) => {

    const user = USER_MARKETING_MANAGER;
    await gotoWebsiteAndTestNavigationForUser(page, URL_MARKETING_INTRANET, user);

})