// import { useState } from "react";
import { IoIosArrowBack } from "react-icons/io";
import { Link, useNavigate } from "react-router";
import Logo from "../../../assets/logo/wbLogo.png";
import ForgotPasswordImg from "../../../assets/Auth/ForgotPassword.png";
import UseAxiosPublic from "../../../Hooks/useAxiosPublic";
import { useState } from "react";
import { toast } from "react-toastify";
import UseAuth from "../../../Hooks/UseAuth";
import { Translations } from "../../../utils/Translations";

const ForgotPassword = () => {
    const { language } = UseAuth();
    const t = Translations[language];
    const axiosPublic = UseAxiosPublic();
    const navigate = useNavigate();
    const [loading, setLoading] = useState(false);

    const handleAction = async (e) => {
        e.preventDefault();
        setLoading(true);
        const mobile = e.target.mobile.value;

        if (!/^\d{11}$/.test(mobile)) {
            toast.error("Mobile number must be exactly 11 digits.");
            setLoading(false);
            return;
        }

       
    };
    return (
        <div className="flex items-center bg-base-100">
            {/* Left Illustration */}
            <div className="hidden lg:flex lg:flex-col items-center justify-center relative lg:w-8/12">
                <img
                    src={ForgotPasswordImg}
                    alt="Login image"
                    className="max-w-sm xl:max-w-md h-auto object-cover"
                />
            </div>

            {/* Right Login Card */}
            <div className="h-screen bg-base-200 flex items-center justify-center p-10 w-full lg:w-4/12">
                <div className="w-full max-w-md space-y-6">
                    {/* Logo */}
                    <Link to="/">
                        <img
                            className="w-fit h-10 mx-auto mb-4"
                            src={Logo}
                            alt="Logo"
                        />
                    </Link>

                    {/* Title */}
                    <div>
                        <h2 className="auth_headertext">
                            {language === "en" ? "Forgot Password?" : "পাসওয়ার্ড ভুলে গেছেন?"}
                        </h2>
                        <p className="text-primary-content">
                            {language === "en" ? "Enter your mobile and we'll send you instructions to reset your password" : " আপনার মোবাইল নম্বর লিখুন এবং আমরা আপনাকে আপনার পাসওয়ার্ড রিসেট করার নির্দেশনা পাঠাবো"}
                        </p>
                    </div>

                    {/* Form */}
                    <form onSubmit={handleAction} className="space-y-5">

                        {/* Email */}
                        <div>
                            <label className="labelClass">
                                {t.mobile} <span className="text-error">*</span>
                            </label>
                            <input
                                autoFocus
                                id="mobile"
                                required
                                type="number"
                                placeholder={t.mobile}
                                name="mobile"
                                className="inputClass"
                            />
                        </div>

                        {/* Register Button */}
                        <button
                            type="submit"
                            disabled={loading}
                            className="w-full py-2 btn transition"
                        >
                            {loading ? t.processing : t.send}
                        </button>
                    </form>

                    {/* Create Account */}
                    <p className="text-center text-sm">

                        <Link to="/login" disabled={loading} className="linkClass flex items-center justify-center">
                            <IoIosArrowBack /> {language === "en" ? "Back to Login" : "লগইনে ফিরে যান"}
                        </Link>
                    </p>

                </div>
            </div>
        </div>
    );
};

export default ForgotPassword;