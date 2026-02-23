import React, { useState } from "react";
import { Link, usePage } from "@inertiajs/inertia-react";

const Header = () => {
    const { auth } = usePage().props;
    const user = auth.user;
    const profileImage = user.image
        ? `/storage/uploads/user_img/${user.image}`
        : "/backend/template-assets/images/img_preview.png";

    return (
        <div className="top-navbar">
            <nav className="navbar navbar-expand justify-content-between">
                <div className="container-fluid">
                    <a className="d-inline-block d-lg-none ml-auto more-button">
                        <span className="material-symbols-outlined">menu</span>
                    </a>

                    <a
                        id="sidebarCollapse"
                        className="d-lg-block d-none position-relative"
                    >
                        <span className="material-symbols-outlined">menu</span>
                    </a>

                    <a
                        className="navbar-brand d-lg-block d-none custom-d-none-color"
                        href="/admin/dashboard"
                    >
                        Dashboard
                    </a>

                    <div className="collapse navbar-collapse justify-content-end custom-header-menu-icon">
                        <ul className="nav navbar-nav ml-auto">
                            <li className="nav-item active">
                                <a
                                    href="javascript:void(0)"
                                    className="nav-link"
                                    data-bs-toggle="modal"
                                    data-bs-target="#filterClient"
                                >
                                    <span className="material-symbols-outlined">
                                        group
                                    </span>
                                </a>
                            </li>

                            <li className="nav-item active">
                                <a href="#" className="nav-link">
                                    <span className="material-symbols-outlined">
                                        storefront
                                    </span>
                                </a>
                            </li>

                            <li className="nav-item dropdown shortcut">
                                <a
                                    className="nav-link"
                                    href="#"
                                    data-bs-toggle="dropdown"
                                >
                                    <span className="material-symbols-outlined">
                                        apps
                                    </span>
                                </a>
                                <ul className="dropdown-menu shortcut overflow-hidden p-0">
                                    <li className="navbar px-4">
                                        <span className="text">Shortcut</span>
                                        <span className="material-symbols-outlined">
                                            dataset
                                        </span>
                                    </li>
                                    <hr className="m-0" />
                                    <li className="row m-0">
                                        <div className="col-6 border text-center py-2">
                                            <a href="#">
                                                <p className="text m-0">
                                                    <span className="material-symbols-outlined">
                                                        list
                                                    </span>
                                                </p>
                                                <p className="m-0 super-small">
                                                    <small>Category</small>
                                                </p>
                                            </a>
                                        </div>
                                        <div className="col-6 border text-center py-2">
                                            <a href="#">
                                                <p className="text m-0">
                                                    <span className="material-symbols-outlined">
                                                        medical_information
                                                    </span>
                                                </p>
                                                <p className="m-0 super-small">
                                                    <small>Service</small>
                                                </p>
                                            </a>
                                        </div>
                                    </li>
                                    <li className="row m-0">
                                        <div className="col-6 border text-center py-2">
                                            <a href="#">
                                                <p className="text m-0">
                                                    <span className="material-symbols-outlined">
                                                        group
                                                    </span>
                                                </p>
                                                <p className="m-0 super-small">
                                                    <small>Client</small>
                                                </p>
                                            </a>
                                        </div>
                                        <div className="col-6 border text-center py-2">
                                            <a href="#">
                                                <p className="text m-0">
                                                    <span className="material-symbols-outlined">
                                                        group
                                                    </span>
                                                </p>
                                                <p className="m-0 super-small">
                                                    <small>Provider</small>
                                                </p>
                                            </a>
                                        </div>
                                    </li>
                                    <li className="row m-0">
                                        <div className="col-6 border text-center py-2">
                                            <a href="#">
                                                <p className="text m-0">
                                                    <span className="material-symbols-outlined">
                                                        payments
                                                    </span>
                                                </p>
                                                <p className="m-0 super-small">
                                                    <small>Payment Type</small>
                                                </p>
                                            </a>
                                        </div>
                                        <div className="col-6 border text-center py-2">
                                            <a href="#">
                                                <p className="text m-0">
                                                    <span className="material-symbols-outlined">
                                                        meeting_room
                                                    </span>
                                                </p>
                                                <p className="m-0 super-small">
                                                    <small>Branch</small>
                                                </p>
                                            </a>
                                        </div>
                                    </li>
                                </ul>
                            </li>

                            <li className="nav-item dropdown">
                                <a
                                    className="nav-link"
                                    data-bs-toggle="dropdown"
                                    href="#"
                                >
                                    <img
                                        src={profileImage}
                                        alt="profile"
                                        className="rounded-circle"
                                        width="30"
                                        height="30"
                                    />
                                </a>
                                <ul className="dropdown-menu">
                                    <li>
                                        <Link
                                            href="/admin/profile"
                                            className="d-flex align-items-center"
                                        >
                                            <img
                                                src={profileImage}
                                                className="rounded-circle me-2"
                                                alt=""
                                                width="30"
                                                height="30"
                                            />
                                            <div className="ms-2">
                                                <p className="text m-0">
                                                    {user?.name}
                                                </p>
                                                <p className="m-0">
                                                    <small>{user?.role}</small>
                                                </p>
                                            </div>
                                        </Link>
                                    </li>
                                    <hr />
                                    <li className="nav-item">
                                        <Link href="/admin/profile">
                                            <span className="material-symbols-outlined">
                                                person
                                            </span>
                                            <span className="text">
                                                Profile
                                            </span>
                                        </Link>
                                    </li>
                                    <li className="nav-item">
                                        <Link href="/admin/logout">
                                            <span className="material-symbols-outlined">
                                                logout
                                            </span>
                                            <span className="text">Logout</span>
                                        </Link>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
        </div>
    );
};

export default Header;
