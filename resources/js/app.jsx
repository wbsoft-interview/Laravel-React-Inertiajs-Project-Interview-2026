import { createInertiaApp } from "@inertiajs/inertia-react";
import { createRoot } from "react-dom/client";
import React from "react";

const pages = import.meta.glob("./Pages/**/*.jsx"); // Vite magic: all JSX under Pages

createInertiaApp({
    resolve: (name) => {
        const page = pages[`./Pages/${name}.jsx`]; // name = "Backend/Dashboard"
        if (!page) throw new Error(`Page not found: ${name}`);
        return page(); // dynamic import
    },
    setup({ el, App, props }) {
        const root = createRoot(el);
        root.render(<App {...props} />);
    },
});
