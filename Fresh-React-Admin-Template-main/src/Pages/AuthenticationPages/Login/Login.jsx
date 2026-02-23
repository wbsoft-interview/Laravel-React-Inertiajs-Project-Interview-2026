import { AnimatePresence, motion } from "framer-motion";
import { useEffect, useState } from "react";
import { FaEye, FaEyeSlash, FaFacebook, FaGithub, FaGoogle, FaTwitter } from "react-icons/fa";
import { Link, useNavigate } from "react-router";
import { toast } from "react-toastify";
import login from "../../../assets/Auth/login.png";
import Logo from "../../../assets/logo/wbLogo.png";
import useAxiosPublic from "../../../Hooks/useAxiosPublic";
import UseAuth from "../../../Hooks/UseAuth";
import { Translations } from "../../../utils/Translations";

const pageFade = {
    hidden: { opacity: 0 },
    show: { opacity: 1, transition: { duration: 0.5 } },
};

const leftImage = {
    hidden: { opacity: 0, x: -40, scale: 0.98 },
    show: { opacity: 1, x: 0, scale: 1, transition: { duration: 0.7, ease: "easeOut" } },
};

const cardPop = {
    hidden: { opacity: 0, y: 28, scale: 0.98 },
    show: { opacity: 1, y: 0, scale: 1, transition: { duration: 0.6, ease: "easeOut" } },
};

const formSwap = {
    initial: { opacity: 0, y: 10, filter: "blur(4px)" },
    animate: { opacity: 1, y: 0, filter: "blur(0px)", transition: { duration: 0.28, ease: "easeOut" } },
    exit: { opacity: 0, y: -10, filter: "blur(4px)", transition: { duration: 0.18, ease: "easeIn" } },
};

const tabIndicator = {
    phone: { x: "0%" },
    mail: { x: "100%" },
};

const Login = () => {
    const [showPassword, setShowPassword] = useState(false);
    const { language } = UseAuth();
    const t = Translations[language];
    const [loading, setLoading] = useState(false);
    const [loginMobile, setLoginMobile] = useState(true);

    // optional: reset password visibility when switching
    useEffect(() => {
        setShowPassword(false);
    }, [loginMobile]);

    const handleLogin = async (e) => {
        e.preventDefault();
        setLoading(true);

        const mobile = e.target.mobile.value;
        const password = e.target.password.value;

        const info = { mobile, password };
        console.log(info)

    };

    const handleLoginEmail = async (e) => {
        e.preventDefault();
        setLoading(true);

        const email = e.target.email.value;
        const password = e.target.password.value;

        const info = { email, password };
        console.log(info)

    };

    return (
        <motion.div
            variants={pageFade}
            initial="hidden"
            animate="show"
            className="flex items-center bg-base-100"
        >
            {/* Left Illustration */}
            <motion.div
                variants={leftImage}
                initial="hidden"
                animate="show"
                className="hidden lg:flex lg:flex-col items-center justify-center relative lg:w-8/12"
            >
                <motion.img
                    src={login}
                    alt="Login illustration"
                    className="max-w-lg 2xl:max-w-xl h-auto object-cover"
                    whileHover={{ scale: 1.02 }}
                    transition={{ type: "spring", stiffness: 220, damping: 18 }}
                />
            </motion.div>

            {/* Right Login Card */}
            <div className="h-screen bg-base-200 flex items-center justify-center p-10 w-full lg:w-4/12">
                <motion.div
                    variants={cardPop}
                    initial="hidden"
                    animate="show"
                    className="w-full max-w-md space-y-6"
                >
                    {/* Logo */}
                    <Link to="/">
                        <motion.img
                            className="w-fit h-10 mx-auto mb-16"
                            src={Logo}
                            alt="Logo"
                            initial={{ opacity: 0, y: -10 }}
                            animate={{ opacity: 1, y: 0 }}
                            transition={{ duration: 0.4, ease: "easeOut" }}
                        />
                    </Link>

                    {/* Title */}
                    <div>
                        <h2 className="auth_headertext">
                            {t.welcome} <span className="capitalize">MAGIC RESULT</span>! 👋
                        </h2>
                        <p className="text-primary-content">
                            {language === "en" ? "Please sign in to your account and start the adventure" : "আপনার অ্যাকাউন্টে সাইন ইন করুন এবং অ্যাডভেঞ্চার শুরু করুন।"}
                        </p>
                    </div>

                    {/* Tabs with animated indicator */}
                    <div className="relative flex items-center w-full rounded-md border border-accent overflow-hidden">
                        <motion.div
                            className="absolute inset-y-0 w-1/2 bg-primary-light"
                            variants={tabIndicator}
                            animate={loginMobile ? "phone" : "mail"}
                            transition={{ type: "spring", stiffness: 260, damping: 22 }}
                        />

                        <button
                            type="button"
                            onClick={() => setLoginMobile(true)}
                            className={`relative z-10 w-full p-2 font-bold text-base cursor-pointer hover:bg-primary-hover hover:text-primary-content ${loginMobile ? "text-primary" : "text-primary-content"
                                }`}
                        >{language === "en" ? "Login With Phone" : "ফোন দিয়ে লগইন করুন"}
                        </button>

                        <button
                            type="button"
                            onClick={() => setLoginMobile(false)}
                            className={`relative z-10 w-full p-2 font-bold text-base cursor-pointer hover:bg-primary-hover hover:text-primary-content ${loginMobile ? "text-primary-content" : "text-primary"
                                }`}
                        >{language === "en" ? "Login With Mail" : "মেইল দিয়ে লগইন করুন"}
                        </button>
                    </div>

                    {/* Form (animated swap) */}
                    <AnimatePresence mode="wait">
                        {loginMobile ? (
                            <motion.form
                                key="phoneForm"
                                onSubmit={handleLogin}
                                className="space-y-5"
                                variants={formSwap}
                                initial="initial"
                                animate="animate"
                                exit="exit"
                            >
                                {/* Mobile */}
                                <div>
                                    <label htmlFor="mobile" className="labelClass">
                                        {t.mobile}
                                    </label>
                                    <input
                                        autoFocus
                                        id="mobile"
                                        type="number"
                                        placeholder="01*********"
                                        name="mobile"
                                        required
                                        className="inputClass"
                                    />
                                </div>

                                {/* Password */}
                                <div>
                                    <label htmlFor="password" className="labelClass">
                                        {t.password}
                                    </label>
                                    <div className="relative">
                                        <input
                                            type={showPassword ? "text" : "password"}
                                            id="password"
                                            placeholder="********"
                                            name="password"
                                            required
                                            className="inputClass"
                                        />
                                        <button
                                            type="button"
                                            onClick={() => setShowPassword(!showPassword)}
                                            className="absolute top-1/2 right-4 -translate-y-1/3 text-primary-content cursor-pointer"
                                        >
                                            {showPassword ? <FaEyeSlash size={18} /> : <FaEye size={18} />}
                                        </button>
                                    </div>
                                </div>

                                {/* Options */}
                                <div className="flex justify-between items-center text-sm">
                                    <label className="flex items-center gap-2">
                                        <input className="checkbox bg-base-200" type="checkbox" />
                                        <span className="h-full text-primary-content">{language === "en" ? "Remember me" : "আমাকে মনে রেখো"}</span>
                                    </label>
                                    <Link to="/forgot-password" className="linkClass">
                                        {language === "en" ? "Forgot Password?" : "পাসওয়ার্ড ভুলে গেছেন?"}
                                    </Link>
                                </div>

                                {/* Login Button */}
                                <motion.button
                                    type="submit"
                                    disabled={loading}
                                    whileTap={{ scale: 0.98 }}
                                    whileHover={{ scale: loading ? 1 : 1.01 }}
                                    className={`w-full py-2 btn transition ${loading ? "opacity-70 cursor-not-allowed" : ""}`}
                                >
                                    {loading ? t.login : t.login}
                                </motion.button>
                            </motion.form>
                        ) : (
                            <motion.form
                                key="mailForm"
                                onSubmit={handleLoginEmail}
                                className="space-y-5"
                                variants={formSwap}
                                initial="initial"
                                animate="animate"
                                exit="exit"
                            >
                                {/* Email */}
                                <div>
                                    <label htmlFor="email" className="labelClass">
                                        {t.email}
                                    </label>
                                    <input
                                        autoFocus
                                        id="email"
                                        type="email"
                                        placeholder={t.email}
                                        name="email"
                                        required
                                        className="inputClass"
                                    />
                                </div>

                                {/* Password */}
                                <div>
                                    <label htmlFor="password" className="labelClass">
                                        {t.password}
                                    </label>
                                    <div className="relative">
                                        <input
                                            type={showPassword ? "text" : "password"}
                                            id="password"
                                            placeholder="********"
                                            name="password"
                                            required
                                            className="inputClass"
                                        />
                                        <button
                                            type="button"
                                            onClick={() => setShowPassword(!showPassword)}
                                            className="absolute top-1/2 right-4 -translate-y-1/3 text-primary-content cursor-pointer"
                                        >
                                            {showPassword ? <FaEyeSlash size={18} /> : <FaEye size={18} />}
                                        </button>
                                    </div>
                                </div>

                                {/* Options */}
                                <div className="flex justify-between items-center text-sm">
                                    <label className="flex items-center gap-2">
                                        <input className="checkbox bg-base-200" type="checkbox" />
                                        <span className="h-full text-primary-content">{language === "en" ? "Remember me" : "আমাকে মনে রেখো"}</span>
                                    </label>
                                    <Link to="/forgot-password" className="linkClass">
                                        {language === "en" ? "Forgot Password?" : "পাসওয়ার্ড ভুলে গেছেন?"}
                                    </Link>
                                </div>

                                {/* Login Button */}
                                <motion.button
                                    type="submit"
                                    disabled={loading}
                                    whileTap={{ scale: 0.98 }}
                                    whileHover={{ scale: loading ? 1 : 1.01 }}
                                    className={`w-full py-2 btn transition ${loading ? "opacity-70 cursor-not-allowed" : ""}`}
                                >
                                    {loading ? t.login : t.login}
                                </motion.button>
                            </motion.form>
                        )}
                    </AnimatePresence>

                    {/* Create Account */}
                    <p className="text-center text-sm">
                        {language === "en" ? "Don’t have an account? " : "কোন অ্যাকাউন্ট নেই? "}
                        <Link to="/register" className="linkClass">
                            {language === "en" ? "Register" : "নিবন্ধন"}
                        </Link>
                    </p>

                    {/* Divider */}
                    <div className="flex items-center">
                        <hr className="grow border-accent" />
                        <span className="mx-4 text-primary">{t.or}</span>
                        <hr className="grow border-accent" />
                    </div>

                    {/* Social Login (hover animation) */}
                    <div className="flex justify-center gap-4 text-xl">
                        <motion.div whileHover={{ scale: 1.12 }} whileTap={{ scale: 0.95 }}>
                            <FaFacebook className="cursor-pointer text-[#4267b2]" />
                        </motion.div>
                        <motion.div whileHover={{ scale: 1.12 }} whileTap={{ scale: 0.95 }}>
                            <FaTwitter className="cursor-pointer text-[#1da1f2]" />
                        </motion.div>
                        <motion.div whileHover={{ scale: 1.12 }} whileTap={{ scale: 0.95 }}>
                            <FaGithub className="cursor-pointer text-gray-800" />
                        </motion.div>
                        <motion.div whileHover={{ scale: 1.12 }} whileTap={{ scale: 0.95 }}>
                            <FaGoogle className="cursor-pointer text-[#dd4b39]" />
                        </motion.div>
                    </div>
                </motion.div>
            </div>
        </motion.div>
    );
};

export default Login;