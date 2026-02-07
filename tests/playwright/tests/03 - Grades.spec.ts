import {expect, Page, test} from "@playwright/test";
import {URL_GRADES_INTRANET, URL_MARKETING_INTRANET} from "./lib/urls";
import {
    USER_MARKETING_MANAGER,
    USER_MARKETING_NORMAL, USER_STUDENT, USER_TEACHER,
} from "./lib/TestUserInfo";
import {gotoWebsiteAndTestNavigationForUser} from "./lib/NavigationTestSupport";



test('Grades - Student', async ({page}) => {

    await gotoWebsiteAndTestNavigationForUser(page, URL_GRADES_INTRANET, USER_STUDENT);
});

test('Grades - Teacher', async ({page}) => {
    await gotoWebsiteAndTestNavigationForUser(page, URL_GRADES_INTRANET, USER_TEACHER);

})