import {expect, Page, test} from "@playwright/test";
import {URL_ADMIN, URL_ADMIN_INTRANET, URL_GRADES_INTRANET, URL_HRM, URL_MARKETING_INTRANET} from "./lib/urls";
import {
    USER_HRM, USER_ICT, USER_ICT_AUTHMGR,
    USER_MARKETING_MANAGER,
    USER_MARKETING_NORMAL, USER_STUDENT, USER_TEACHER,
} from "./lib/TestUserInfo";
import {gotoWebsiteAndTestNavigationForUser} from "./lib/NavigationTestSupport";

// DUE to 2FA this test needs to be redesigned.
/*

test('ICT - normal user', async ({page}) => {

    await gotoWebsiteAndTestNavigationForUser(page, URL_ADMIN_INTRANET, USER_ICT);
});

test('ICT - AuthMgr', async ({page}) => {

    await gotoWebsiteAndTestNavigationForUser(page, URL_ADMIN_INTRANET, USER_ICT_AUTHMGR);
});
*/
