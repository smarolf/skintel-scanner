import { defineFlatConfig } from 'eslint-define-config';
import vue from 'eslint-plugin-vue';
import prettier from 'eslint-config-prettier';

export default defineFlatConfig({
  ignores: [
    'vendor',
    'node_modules',
    'public',
    'bootstrap/ssr',
    'tailwind.config.js',
    'resources/js/components/ui/*',
  ],
  plugins: {
    vue,
  },
  languageOptions: {
    parserOptions: {
      ecmaVersion: 'latest',
      sourceType: 'module',
    },
  },
  extends: [
    vue.configs['flat/essential'], // atau 'flat/recommended'
    prettier,
  ],
  rules: {
    'vue/multi-word-component-names': 'off',
  },
});
