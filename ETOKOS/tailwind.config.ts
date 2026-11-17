import type { Config } from "tailwindcss";

const config: Config = {
  content: [
    "./src/pages/**/*.{js,ts,jsx,tsx,mdx}",
    "./src/components/**/*.{js,ts,jsx,tsx,mdx}",
    "./src/app/**/*.{js,ts,jsx,tsx,mdx}",
  ],
  theme: {
    extend: {
      colors: {
        primary: {
          50: "#fff8f1",
          100: "#feecdc",
          200: "#fcd9bd",
          300: "#fdba8c",
          400: "#ff8a4c",
          500: "#ff641a",
          600: "#f04400",
          700: "#cc3200",
          800: "#9f2704",
          900: "#7e2208",
        },
        amber: {
          DEFAULT: "#f59e0b",
        },
      },
    },
  },
  plugins: [],
};
export default config;
