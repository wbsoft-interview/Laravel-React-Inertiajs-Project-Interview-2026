import { createBrowserRouter, Navigate } from "react-router";

/* ===== Layouts ===== */
import AdminLayout from "../Layout/AdminLayout";
import AuthenticationLayout from "../Layout/AuthenticationLayout";

/* ===== Admin Pages ===== */
import Deshboard from "../Pages/AdminPages/Deshboard/Deshboard";
import Login from "../Pages/AuthenticationPages/Login/Login";
import Register from "../Pages/AuthenticationPages/Register/Register";
import OtpVerify from "../Pages/AuthenticationPages/OtpVerify/OtpVerify";
import ResetPassword from "../Pages/AuthenticationPages/ResetPassword/ResetPassword";
import ForgotPassword from "../Pages/AuthenticationPages/ForgotPassword/ForgotPassword";
import TwoSteps from "../Pages/AuthenticationPages/TwoSteps/TwoSteps";
import VerifyEmail from "../Pages/AuthenticationPages/VerifyEmail/VerifyEmail";

import Profile from "../Pages/Profile/Profile";
import ErrorPage from "../Shared/ErrorPage/ErrorPage";
import ForgetOtpVerify from "../Pages/AuthenticationPages/OtpVerify/ForgetOtpVerify";
import Support from "../Pages/AdminPages/Support/Support";
import Table from "../componentes/Table";
import From from "../Pages/From";
import Cardtable from "../Pages/Cardtable";
import Card from "../Pages/Card";


export const router = createBrowserRouter([
    {
        path: "/",
        element: <AdminLayout />,
        children: [
            { index: true, element: <Navigate to="deshboard" replace /> },
            { path: "deshboard", Component: Deshboard },
            { path: "support", Component: Support },
            { path: "profile", Component: Profile },
            { path: "card", Component: Card },
            { path: "table", Component: Table },
            { path: "from", Component: From },
            { path: "cardtable", Component: Cardtable },
            { path: "profile", Component: Profile },
            { path: "support", Component: Support },
            { path: "two-steps", Component: TwoSteps },
            { path: "verify-email", Component: VerifyEmail },
        ],
    },

    /* ADMIN ROUTES */
    {
        path: "/admin",
        element: (
                <AdminLayout />
        ),
        children: [
            { index: true, element: <Navigate to="/deshboard" replace /> },
        ],
    },

    /* AUTH ROUTES */
    {
        path: "/",
        element: (
                <AuthenticationLayout />
        ),
        children: [
            { path: "login", Component: Login },
            { path: "register", Component: Register },
            { path: "otp-verify", Component: OtpVerify },
            { path: "forgot-password", Component: ForgotPassword },
            { path: "forgot-pass-verify-otp", Component: ForgetOtpVerify },
            { path: "reset-password", Component: ResetPassword },
            { path: "two-steps", Component: TwoSteps },
            { path: "verify-email", Component: VerifyEmail },
        ],
    },

    /* ERROR */
    {
        path: "*",
        Component: ErrorPage,
    },
]);
