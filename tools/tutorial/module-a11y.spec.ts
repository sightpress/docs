// The accessibility check of "Create a module": on a SightPress website with the tutorial's Events module
// (module-site.php), the first admin sets up their account, then adds an event by keyboard on the Events screen,
// including a form with mistakes, and opens its public page. No axe violations (WCAG 2.2 AA) in light and dark mode.
import AxeBuilder from '@axe-core/playwright';
import { expect, test, type Page } from '@playwright/test';

const SITE = process.env.SIGHTPRESS_TUTORIAL_SITE ?? 'http://127.0.0.1:8123';
const PASSWORD = 'Correct horse battery staple 7!';

async function expectNoViolations(page: Page): Promise<void> {
  await page.waitForLoadState('load');
  for (const colorScheme of ['light', 'dark'] as const) {
    await page.emulateMedia({ colorScheme });
    const results = await new AxeBuilder({ page }).withTags(['wcag2a', 'wcag2aa', 'wcag21a', 'wcag21aa', 'wcag22aa']).analyze();
    expect(results.violations, `${page.url()} in ${colorScheme} mode`).toEqual([]);
  }
}

test('the Events screen and an event page pass axe and work by keyboard', async ({ page, request }) => {
  const code = (await (await request.get(`${SITE}/_test/setup-code`)).text()).trim();
  await page.goto(`${SITE}/login/setup`);
  await page.getByLabel('Email address').fill('ada@example.org');
  await page.getByLabel('6-digit code').fill(code);
  await page.getByRole('button', { name: 'Continue' }).click();
  await expect(page.getByRole('heading', { level: 1 })).toHaveText('Choose your password');
  await page.getByLabel('New password').fill(PASSWORD);
  await page.getByRole('button', { name: 'Set password' }).click();
  await expect(page).toHaveURL(`${SITE}/login`);
  await page.getByLabel('Email address').fill('ada@example.org');
  await page.getByLabel('Password', { exact: true }).fill(PASSWORD);
  await page.getByRole('button', { name: 'Sign in' }).click();
  await expect(page).not.toHaveURL(/\/login/);

  await page.goto(`${SITE}/admin/events`);
  await expect(page.getByRole('heading', { level: 1 })).toHaveText('Events');
  await expect(page.getByText('No events yet. Add the first one below.')).toBeVisible();
  await expect(page.getByRole('navigation', { name: 'Admin' }).getByRole('link', { name: 'Events' })).toBeVisible();
  await expectNoViolations(page);

  // A form with mistakes says what to fix, next to each field.
  await page.getByRole('button', { name: 'Add event' }).focus();
  await page.keyboard.press('Enter');
  await expect(page.getByLabel('Name')).toHaveAttribute('aria-invalid', 'true');
  await expect(page.getByText('Error: Enter the name of the event.')).toBeVisible();
  await expect(page.getByText('Error: Enter the date and time it starts.')).toBeVisible();
  await expectNoViolations(page);

  await page.getByLabel('Name').focus();
  await page.keyboard.type('Open day');
  await page.getByLabel('Starts').fill('2026-12-05T10:00');
  await page.getByLabel('Starts').press('Enter');
  await expect(page.getByRole('status')).toHaveText('Added “Open day”.');
  await expect(page.getByRole('table', { name: 'Events, soonest first' }).getByRole('link', { name: 'Open day' })).toBeVisible();
  await expectNoViolations(page);

  await page.getByRole('link', { name: 'Open day' }).click();
  await expect(page.getByRole('heading', { level: 1 })).toHaveText('Open day');
  await expectNoViolations(page);
});
