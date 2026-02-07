import {expect, test} from "@playwright/test";
import {ALL_DOCKER_URLS} from "./lib/urls";


test('test', async ({page}) => {

    for (let url of ALL_DOCKER_URLS) {

        await page.goto(`http://${url}`);
        await expect(page.getByRole('link', {name: 'intranet'})).toBeVisible();
    }
});