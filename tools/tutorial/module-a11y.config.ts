// Runs module-a11y.spec.ts (see create-a-module.sh), against the website module-site.php serves.
import { defineConfig, devices } from '@playwright/test';

export default defineConfig({
  testDir: '.',
  testMatch: 'module-a11y.spec.ts',
  outputDir: '../../build/tutorial-results',
  reporter: 'list',
  projects: [{ name: 'chromium', use: { ...devices['Desktop Chrome'] } }],
});
