import { useState } from 'react';
import { FaEye, FaEyeSlash, FaFacebook, FaGithub, FaGoogle, FaTwitter } from 'react-icons/fa';
import { Link, useNavigate } from 'react-router';
import Logo from "../../../assets/logo/wbLogo.png";
import RegisterImg from "../../../assets/Auth/Register.png";
import RegisterImgDark from "../../../assets/Auth/Register_dark.png";
import { toast } from 'react-toastify';
import UseAxiosPublic from '../../../Hooks/useAxiosPublic';

const Register = () => {
    // const [theme] = useState(localStorage.getItem("theme") || "light");

    const [showPassword, setShowPassword] = useState(false);
    const [showConfirmationPassword, setShowConfirmationPassword] = useState(false);
    const [loading, setLoading] = useState(false);

    const handleRegister = async (e) => {
        e.preventDefault();
        setLoading(true);

        const name = e.target.name.value;
        const institute_name_english = e.target.institute_name_english.value;
        const email = e.target.email.value;
        const unique_mobile = e.target.mobile.value;
        const password = e.target.password.value;
        const password_confirmation = e.target.password_confirmation.value;

        if (!/^\d{11}$/.test(unique_mobile)) {
            toast.error("Mobile number must be exactly 11 digits.");
            setLoading(false);
            return;
        }

        const info = { name, institute_name_english, email, unique_mobile, password, password_confirmation };

    }

    return (
        <div className="flex items-center bg-base-100">
            {/* Left Illustration */}
            <div className="hidden lg:flex lg:flex-col items-center justify-center relative lg:w-8/12">
                <img
                    src={
                        // theme === "light" ?
                        "light" === "light" ?
                            RegisterImg
                            : RegisterImgDark
                    }
                    alt="Login image"
                    className="max-w-lg 2xl:max-w-xl h-auto object-cover"
                />
            </div>

            {/* Right Login Card */}
            <div className="h-screen overflow-y-auto flex justify-center bg-base-200 p-10 w-full lg:w-4/12">
                <div className="w-full space-y-6">
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
                        <h2 className="auth_headertext ">
                            Adventure starts here 🚀
                        </h2>
                        <p className="text-primary-content">
                            Make your result management easy and fun!
                        </p>
                    </div>

                    {/* Form */}
                    <form onSubmit={handleRegister} className="space-y-5">
                        {/* Username */}
                        <div>
                            <label className="labelClass ">
                                Name <span className="text-error">*</span>
                            </label>
                            <input
                                autoFocus
                                id="name"
                                required
                                type="text"
                                placeholder="Enter Your Name...."
                                name="name"
                                className="inputClass"
                            />
                        </div>
                        <div>
                            <label className="labelClass ">
                                Institute Name English <span className="text-error">*</span>
                            </label>
                            <input
                                autoFocus
                                id="institute_name_english"
                                required
                                type="text"
                                placeholder="Enter Your Institute Name English...."
                                name="institute_name_english"
                                className="inputClass"
                            />
                        </div>

                        <div>
                            <label className="labelClass ">
                                Email (optional)
                            </label>
                            <input
                                autoFocus
                                id="email"
                                type="email"
                                placeholder="Enter Your email"
                                name="email"
                                className="inputClass"
                            />
                        </div>

                        {/* Mobile */}
                        <div>
                            <label className="labelClass">
                                Mobile <span className="text-error">*</span>
                            </label>
                            <input
                                autoFocus
                                id="mobile"
                                type="mobile"
                                required
                                placeholder="01*********"
                                name="mobile"
                                className="inputClass"
                            />
                        </div>

                        {/* Password with Show/Hide */}
                        <div>
                            <label className="labelClass ">
                                Password <span className="text-error">*</span>
                            </label>
                            <div className="relative">
                                <input
                                    type={showPassword ? "text" : "password"}
                                    required
                                    id="password"
                                    placeholder="*********"
                                    name="password"
                                    className="inputClass"
                                />
                                <button
                                    type="button"
                                    onClick={() => setShowPassword(!showPassword)}
                                    className="absolute top-1/2 right-4 -translate-y-1/3 text-primary-content cursor-pointer"
                                >
                                    {showPassword ? <FaEyeSlash /> : <FaEye />}
                                </button>
                            </div>
                        </div>

                        <div>
                            <label className="labelClass ">
                                Confirm Password <span className="text-error">*</span>
                            </label>
                            <div className="relative">
                                <input
                                    type={showConfirmationPassword ? "text" : "password"}
                                    required
                                    id="password_confirmation"
                                    placeholder="*********"
                                    name="password_confirmation"
                                    className="inputClass"
                                />
                                <button
                                    type="button"
                                    onClick={() => setShowConfirmationPassword(!showConfirmationPassword)}
                                    className="absolute top-1/2 right-4 -translate-y-1/3 text-primary-content cursor-pointer"
                                >
                                    {showConfirmationPassword ? <FaEyeSlash /> : <FaEye />}
                                </button>
                            </div>
                        </div>

                        {/* Options */}
                        <div className="flex justify-between items-center text-sm">
                            <label className="flex items-center gap-2">
                                <input className="checkbox bg-base-200" type="checkbox" />
                                <p className="h-full">
                                    I agree to{" "}
                                    <Link
                                        to="/privacy-policy"
                                        className="linkClass"
                                    >
                                        privacy policy & terms
                                    </Link>
                                </p>
                            </label>
                        </div>

                        {/* Register Button */}
                        <button
                            type="submit"
                            disabled={loading}
                            className="w-full py-2 btn transition"
                        >
                            Register
                        </button>
                    </form>

                    {/* Sign in */}
                    <p className="text-center text-sm">
                        Already have an account?{" "}
                        <Link to="../login" className="linkClass">
                            Login
                        </Link>
                    </p>

                    {/* Divider */}
                    <div className="flex items-center">
                        <hr className="grow border-accent" />
                        <span className="mx-4 text-primary">or</span>
                        <hr className="grow border-accent" />
                    </div>

                    {/* Social Login */}
                    <div className="flex justify-center gap-4  pb-10 text-xl">
                        <FaFacebook className="cursor-pointer text-[#4267b2] hover:scale-110 transition" />
                        <FaTwitter className="cursor-pointer text-[#1da1f2] hover:scale-110 transition" />
                        <FaGithub className="cursor-pointer text-gray-800  hover:scale-110 transition" />
                        <FaGoogle className="cursor-pointer text-[#dd4b39] hover:scale-110 transition" />
                    </div>
                </div>
            </div>
        </div>
    );
};

export default Register;