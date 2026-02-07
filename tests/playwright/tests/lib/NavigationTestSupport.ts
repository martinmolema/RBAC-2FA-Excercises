import {expect, Page} from "@playwright/test";
import {DockerWebUser, GetRoutesInHeaderForWebsite} from "./TestUserInfo";
import {URL_PORTAL, URL_SHAREPOINT_INTRANET} from "./urls";


export async function gotoWebsiteAndTestNavigationForUser(page: Page, url: string, user: DockerWebUser): Promise<void> {
    return new Promise<void>(async (resolve, reject) => {
        try {
            const logoutURL =`http://${URL_PORTAL}/logout.php`;
            await page.goto(logoutURL);

            await page.goto(`http://${url}`);

            await expect(page.url()).toContain(`http://${URL_PORTAL}/?redirect`);

            const usernameField = page.getByRole('textbox', {name:'username'});
            const passwordField = page.getByRole('textbox', {name:'password'});
            await usernameField.fill(user.username);
            await passwordField.fill(user.password);
            await page.getByRole('button',{name:'submit'}).click();


            const nav = GetRoutesInHeaderForWebsite(user.role);

            for (let link of nav) {
                const linkElement = await page.locator(`a[href="${link.route}"]`);
                await expect(linkElement).toBeVisible();
                await expect(linkElement).toHaveText(link.title)
            }

            resolve();
        }
        catch (error) {
            reject(error);
        }
    });
}