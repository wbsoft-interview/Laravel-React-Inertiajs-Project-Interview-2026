import React, { useState, useEffect } from "react";
import { Link, usePage } from "@inertiajs/inertia-react";

const Sidebar = () => {
    const { url, props } = usePage();
    const can = props.auth?.can || {};

    const isActive = (path, exact = true) => {
        if (exact) {
            return url === path;
        }
        return url.startsWith(path);
    };

    const activePrefix = url.startsWith("/") ? url.substring(1).split("/")[0] : url.split("/")[0];

    const menuGroups = {
        accounts: ["account", "account-profile", "account-category"],
        subscriptions: [
            "package",
            "package-category",
            "purchase-account",
            "purchase-account-list",
            "purchase-account-profile",
        ],
    };

    const isMenuActive = (group) => menuGroups[group].includes(activePrefix);

    const [openAccounts, setOpenAccounts] = useState(isMenuActive("accounts"));
    const [openSubscriptions, setOpenSubscriptions] = useState(
        isMenuActive("subscriptions")
    );

    useEffect(() => {
        if (isMenuActive("accounts")) setOpenAccounts(true);
        if (isMenuActive("subscriptions")) setOpenSubscriptions(true);
    }, [url]);

    return (
        <nav id="sidebar">
            <div className="sidebar-header">
                <h3>
                    <img
                        src="/backend/billing_invoice_logo.png"
                        alt="Logo"
                        className="img-fluid custom-sidebar-logo"
                    />
                </h3>
            </div>

            <ul className="list-unstyled components custom-sidebar-menu-icon">
                <li className={isActive("/admin/dashboard") ? "active" : ""}>
                    <Link
                        href="/admin/dashboard"
                        className={`dashboard ${
                            isActive("/admin/dashboard")
                                ? "custom-sidebar-menu-active"
                                : ""
                        }`}
                    >
                        <i className="material-symbols-outlined">dashboard</i>
                        <span>Dashboard</span>
                    </Link>
                </li>

                {can["account-list"] && (
                    <div className="small-screen navbar-display">
                        <li className="dropdown">
                            <a
                                href="#"
                                className={`dropdown-toggle ${
                                    isMenuActive("accounts")
                                        ? "custom-sidebar-menu-active"
                                        : ""
                                }`}
                                aria-expanded={openAccounts}
                                onClick={(e) => {
                                    e.preventDefault();
                                    setOpenAccounts(!openAccounts);
                                }}
                            >
                                <i className="material-symbols-outlined">clinical_notes</i>
                                <span>Accounts</span>
                            </a>

                            <ul className={`collapse list-unstyled menu ${openAccounts ? "show custom-sidebar-open" : ""}`}>
                                <li className={`ms-2 ${
                                        (isActive("/account", false) || isActive("/account-profile", false)) 
                                        ? "custom-sidebar-submenu-active" 
                                        : ""
                                    }`}>
                                    <Link href="/account">
                                        <i className="fa-regular fa-circle sidebar-li"></i>
                                        Account List
                                    </Link>
                                </li>
                            </ul>
                        </li>
                    </div>
                )}

                {(can["package-category-list"] || can["package-list"]) && (
                    <div className="small-screen navbar-display">
                        <li className="dropdown">
                            <a
                                href="#"
                                className={`dropdown-toggle ${
                                    isMenuActive("subscriptions")
                                        ? "custom-sidebar-menu-active"
                                        : ""
                                }`}
                                aria-expanded={openSubscriptions}
                                onClick={(e) => {
                                    e.preventDefault();
                                    setOpenSubscriptions(!openSubscriptions);
                                }}
                            >
                                <i className="material-symbols-outlined">subscriptions</i>
                                <span>Subscriptions</span>
                            </a>

                            <ul className={`collapse list-unstyled menu ${openSubscriptions ? "show custom-sidebar-open" : ""}`}>
                                {can["package-category-list"] && (
                                    <li className={`ms-2 ${isActive("/package-category") ? "custom-sidebar-submenu-active" : ""}`}>
                                        <Link href="/package-category">
                                            <i className="fa-regular fa-circle sidebar-li"></i>
                                            Category List
                                        </Link>
                                    </li>
                                )}

                                {can["package-list"] && (
                                    <li className={`ms-2 ${isActive("/package") ? "custom-sidebar-submenu-active" : ""}`}>
                                        <Link href="/package">
                                            <i className="fa-regular fa-circle sidebar-li"></i>
                                            Package List
                                        </Link>
                                    </li>
                                )}

                                {can["package-list"] && (
                                    <li className={`ms-2 ${
                                        (isActive("/purchase-account-list", false) || isActive("/purchase-account-profile", false)) 
                                        ? "custom-sidebar-submenu-active" 
                                        : ""
                                    }`}>
                                        <Link href="/purchase-account-list">
                                            <i className="fa-regular fa-circle sidebar-li"></i>
                                            Purchase Account
                                        </Link>
                                    </li>
                                )}
                            </ul>
                        </li>
                    </div>
                )}
            </ul>
        </nav>
    );
};

export default Sidebar;