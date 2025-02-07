module.exports = {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
        "./node_modules/flowbite/**/*.js",
    ],
    theme: {
        extend: {
            colors: {
                primary: "#4A90E2",
                customBlack: "#333333",
                content: "#F5F5F5",
                customBlackColor: "#333333",
                customGrayColor: "#F0F0F3",
                customGrayColorDark: "#A4A4AA",
                customGradient:
                    "linear-gradient(to right, #ff5733, #ffbd33)",
            },
            backgroundImage: {
                "gradient-border":
                    "linear-gradient(to right, #069304, #5CD35A)",
            },
            fontSize: {
                custom16: "16px", // Add your custom font size here
            },
        },
    },
    plugins: [
        require("flowbite/plugin")({
            charts: true,
        }),
    ],
};
