import {expect, Page, test} from "@playwright/test";
import {URL_GRADES_INTRANET, URL_HRM, URL_HRM_INTRANET, URL_MARKETING_INTRANET} from "./lib/urls";
import {
    USER_HRM,
    USER_MARKETING_MANAGER,
    USER_MARKETING_NORMAL, USER_STUDENT, USER_TEACHER,
} from "./lib/TestUserInfo";
import {gotoWebsiteAndTestNavigationForUser} from "./lib/NavigationTestSupport";



test('HRM - normal user', async ({page}) => {

    await gotoWebsiteAndTestNavigationForUser(page, URL_HRM_INTRANET, USER_HRM);
});
