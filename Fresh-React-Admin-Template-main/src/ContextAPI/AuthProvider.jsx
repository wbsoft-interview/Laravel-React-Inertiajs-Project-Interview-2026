import { useMemo, useState, useEffect } from "react";
import { AuthContext } from "./AuthContext";
import UseAxiosSecure from "../Hooks/UseAxiosSecure";

const AuthProvider = ({ children }) => {
    const axiosSecure = UseAxiosSecure();

    const [leadId, setLeadId] = useState(null);
    const [loading, setLoading] = useState(false);
    const [theme, setTheme] = useState(() => localStorage.getItem("theme") || "light");

    const [language, setLanguage] = useState(() => {
        if (typeof window === "undefined") return "en";
        return localStorage.getItem("language") || "en";
    });

    // Load theme from localStorage on first render
    useEffect(() => {
        const savedTheme = localStorage.getItem("theme") || "light";
        setTheme(savedTheme);
        document.documentElement.classList.remove("light", "dark");
        document.documentElement.classList.add(savedTheme);
    }, []);

    // Apply theme when theme state changes
    useEffect(() => {
        document.documentElement.classList.remove("light", "dark");
        document.documentElement.classList.add(theme);
        localStorage.setItem("theme", theme);
    }, [theme]);

    useEffect(() => {
        document.documentElement.lang = language;
    }, [language]);

    const toggleLanguage = (lang) => {
        const nextLang = lang ?? (language === "en" ? "bn" : "en");
        setLanguage(nextLang);
        localStorage.setItem("language", nextLang);
    };

    // Mock data (API later)
    const data = {/* same data you posted */ };

    const user = data?.userData;
    const hasAccess = data?.userPermissionData;

    const authInfo = useMemo(
        () => ({
            // Auth
            user,
            hasAccess,
            theme,
            setTheme,

            // UI / Global
            leadId,
            setLeadId,
            loading,
            setLoading,

            language,
            setLanguage,
            toggleLanguage,
        }),
        [user, hasAccess, leadId, loading, theme, language]
    );

    return (
        <AuthContext.Provider value={authInfo}>
            {children}
        </AuthContext.Provider>
    );
};

export default AuthProvider;
