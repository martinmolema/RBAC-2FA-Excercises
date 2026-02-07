import {chromium, expect, Page, test} from '@playwright/test';
import {
    ALL_TEST_USERS,
    DockerWebUser,
    USER_ROLE_marketing_manager,
    USER_ROLE_medewerker_HRM, USER_ROLE_medewerker_ICT_AuthorisationManager,
    USER_ROLE_medewerker_ICT_Support,
    USER_ROLE_medewerker_marketing,
    USER_ROLE_student,
    USER_ROLE_teacher
} from "./lib/TestUserInfo";
import {
    URL_ADMIN_INTRANET,
    URL_GRADES_INTRANET,
    URL_HRM_INTRANET,
    URL_MARKETING_INTRANET, URL_PORTAL, URL_SHAREPOINT,
    URL_SHAREPOINT_INTRANET
} from "./lib/urls";


export async function gotoSharepointPageAndTestBasics(page: Page, user: DockerWebUser): Promise<void> {
    return new Promise<void>(async (resolve, reject) => {
        try {
            console.log(`- User: ${user.username}`);

            const logoutURL =`http://${URL_PORTAL}/logout.php`;
            console.log(logoutURL);
            await page.goto(logoutURL);

            const url = `http://${URL_SHAREPOINT_INTRANET}`;
            await page.goto(url);

            await expect(page.url()).toContain(`http://${URL_PORTAL}/?redirect`);

            const usernameField = page.getByRole('textbox', {name:'username'});
            const passwordField = page.getByRole('textbox', {name:'password'});
            await usernameField.fill(user.username);
            await passwordField.fill(user.password);
            await page.getByRole('button',{name:'submit'}).click();
            await expect(page.url()).toBe(`http://${URL_SHAREPOINT_INTRANET}/`);

            await expect(page.getByRole('list', {name: 'news'})).toBeVisible();
            const items = await page.getByRole('list', {name: 'news'}).getByRole('listitem');
            const nrOfItems = await items.count();
            await expect(nrOfItems).toBeGreaterThanOrEqual(3);

            await expect(page.getByRole('link', {name: 'Mijn gegevens'})).toBeVisible();

            let tileTitles = [];
            let portaalButton = undefined;
            switch (user.type) {
                case 'staff':
                    portaalButton = await page.getByRole('link', {name: 'Medewerkersportaal'})

                    tileTitles = [
                        'Declareren',
                        'Salarisstroken',
                        'Vitaliteit',
                        'Verlof aanvragen',
                        'Trainingen',
                        'Feedback geven',
                        'Persoonlijke gegevens',
                        'Teamoverzicht',
                        'Projecten',
                        'Documenten',
                        'Onboarding',
                        'Offboarding',
                        'Organigram',
                        'Nieuws',
                        'Evenementen',
                        'Medewerkers-gids',
                        'HR Beleid',
                        'Veiligheid',
                        'IT Support',
                        'Contact HR',
                    ];

                    break;
                case 'student':
                    portaalButton = await page.getByRole('link', {name: 'Studenten Portaal'});
                    tileTitles = [
                        'Studentensport',
                        'Relaxen',
                        'Groepswerk',
                        'Presentatie voorbereiden',
                        'Labwerk doen',
                        'Roosters bekijken',
                        'Elektronische Leeromgeving',
                        'Studievoortgang bekijken',
                        'E-mail controleren',
                        'Online lessen volgen',
                        'Marktplaats',
                        'Hulp vragen via chat',
                    ]
                    break;
            }

            await page.getByRole('link', {name: 'Mijn gegevens'}).click();
            await expect(page.locator('td.value.DistinguishedName')).toHaveText(user.dn)
            await expect(page.locator('td.value.Volledigenaam')).toHaveText(user.naam)
            await expect(page.locator('td.value.Username')).toHaveText(user.username)

            await expect(portaalButton).toBeVisible();
            await portaalButton.click();

            for (const title of tileTitles) {
                await expect(page.getByRole('gridcell', {name: title}).getByText(title)).toBeVisible();
            }

            if (user.role === 'teacher') {
                await page.getByRole('link', {name: 'Docenten Portaal'}).click();

                const teacherModules = [
                    'Lesroosters beheren',
                    'Toetsen maken',
                    'Studenten beoordelen',
                    'Cijferadministratie',
                    'Communicatie met studenten',
                    'Beoordelen van opdrachten',
                    'Studievoortgang volgen',
                    'Vergaderingen plannen',
                    'Handleidingen uploaden',
                    'Lessen online geven',
                ];
                for (const title of teacherModules) {
                    await expect(page.getByRole('gridcell', {name: title}).getByText(title)).toBeVisible();
                }

            }

            let externalSitesAvailable = [];
            switch (user.role) {
                case USER_ROLE_student:
                    externalSitesAvailable.push(URL_GRADES_INTRANET)
                    break;
                case USER_ROLE_medewerker_marketing:
                case USER_ROLE_marketing_manager:
                    externalSitesAvailable.push(URL_MARKETING_INTRANET)
                    break;
                case USER_ROLE_medewerker_HRM:
                    externalSitesAvailable.push(URL_HRM_INTRANET)
                    break;
                case USER_ROLE_medewerker_ICT_Support:
                case USER_ROLE_medewerker_ICT_AuthorisationManager:
                    externalSitesAvailable.push(URL_ADMIN_INTRANET)
                    break;
                case USER_ROLE_teacher:
                    externalSitesAvailable.push(URL_GRADES_INTRANET)
                    break;
            }

            for (const link of externalSitesAvailable) {
                await expect(page.locator(`a[href="http://${link}"]`)).toBeVisible();
            }


            resolve();
        } catch (error) {
            reject(error);
        }
    });
}

test('Test SharePoint basics for all types of users', async ({page}) => {

    for (let user of ALL_TEST_USERS) {

        await gotoSharepointPageAndTestBasics(page, user);
    }
});
