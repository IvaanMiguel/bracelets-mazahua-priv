import defaultTheme from 'tailwindcss/defaultTheme'
import colors from './tailwindcolorpalette'
import forms from '@tailwindcss/forms'

/** @type {import('tailwindcss').Config} */
export default {
  darkMode: 'class',

  content: [
    './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
    './storage/framework/views/*.php',
    './resources/views/**/*.blade.php',
    './resources/js/**/*.vue',
  ],

  theme: {
    extend: {
      fontFamily: {
        sans: ['Roboto', ...defaultTheme.fontFamily.sans],
      },
      colors: {
        light: {
          primary: '#B70072',
          'primary-container': '#FFD9E5',
          'on-primary-container': '#3D0023',
          secondary: '#934173',
          'secondary-container': '#FFD8EA',
          'on-secondary-container': '#3C002A',
          tertiary: '#015AC1',
          'tertiary-container': '#D8E2FF',
          'on-tertiary-container': '#001A41',
          error: '#BA1A1A',
          'error-container': '#FFDAD5',
          'on-error-container': '#410002',
          background: '#FFFBFF',
          'on-background': '#1C1B1B',
          surface: '#FDF8F8',
          'on-surface': '#1C1B1B',
          'surface-variant': '#E7E1E1',
          'on-surface-variant': '#494646',
          outline: '#7A7676',
        },
        dark: {
          primary: '#FFB0CF',
          'on-primary': '#63003C',
          'primary-container': '#8C0056',
          'on-primary-container': '#FFD9E5',
          secondary: '#FFAED9',
          'on-secondary': '#5B1043',
          'secondary-container': '#77295A',
          'on-secondary-container': '#FFD8EA',
          tertiary: '#ADC6FF',
          'on-tertiary': '#002E69',
          'tertiary-container': '#002E69',
          'on-tertiary-container': '#D8E2FF',
          error: '#FFB4AB',
          'on-error': '690004',
          'error-container': '#930009',
          'on-error-container': '#FFDAD5',
          background: '#1C1B1B',
          'on-background': '#E6E1E1',
          surface: '#1C1B1B',
          'on-surface': '#E6E1E1',
          'surface-variant': '#494646',
          'on-surface-variant': '#CBC5C5',
          outline: '#949090',
        },
        ...colors
      },
    },
  },

  // plugins: [forms],
}
