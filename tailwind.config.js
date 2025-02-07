module.exports = {
    content: [
      "./resources/**/*.blade.php",
      "./resources/**/*.js",
      "./resources/**/*.vue",
      "./node_modules/flowbite/**/*.js"
    ],
    theme: {
      extend: {
        colors: {
            content: "#F5F5F5",
            customblack: "#333333",
            customblue: "#3F83F8",
            customGrayColor: "#F0F0F3",
            customGrayColorDark: "#A4A4AA",
            customBlackColor: "#333333",
            'custom-gradient': 'linear-gradient(to right, #ff5733, #ffbd33)',
        },
        backgroundImage: {
            'gradient-border': 'linear-gradient(to right, #069304, #5CD35A)',
          },
        fontSize: {
            custom16: "16px", // Add your custom font size here
        },
      },
    },
    plugins: [
        require('flowbite/plugin')({
            charts: true,
        }),
    ],
  }
