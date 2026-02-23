import { Link } from "react-router";
import Logo from "../../../assets/logo/logo.png";
import TwoStepImg from "../../../assets/Auth/TwoSteps.png";
import { useEffect, useRef } from "react";

const TwoSteps = () => {
    const firstInputRef = useRef(null);

    useEffect(() => {
        // Focus first box when page loads
        if (firstInputRef.current) {
            firstInputRef.current.focus();
        }
    }, []);

    const handleTwoSteps = (e) => {
        e.preventDefault();
        console.log("Email verification skipped");
    };
    return (
        <div className="flex items-center bg-base-100">
            {/* Left Illustration */}
            <div className="hidden lg:flex lg:flex-col items-center justify-center relative lg:w-8/12">
                <img
                    src={TwoStepImg}
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
                            className="w-32 h-12 mx-auto mb-16"
                            src={Logo}
                            alt="Logo"
                        />
                    </Link>

                    {/* Title */}
                    <div>
                        <h2 className="auth_headertext">
                            Two Step Verification 💬
                        </h2>
                        <p className="text-primary-content">
                            We sent a verification code to your mobile. Enter the code from
                            the mobile in the field below.
                        </p>
                    </div>

                    {/* Form */}
                    <form onSubmit={handleTwoSteps} className="space-y-5">
                        {/* Code Inputs */}
                        <div>
                            <label className="labelClass">
                                Type your 6 digit security code
                            </label>
                            <div className="grid grid-cols-6 gap-2">
                                {Array.from({ length: 6 }).map((_, i) => (
                                    <input
                                        key={i}
                                        ref={i === 0 ? firstInputRef : null} // only first one auto-focuses
                                        type="text"
                                        maxLength={1}
                                        name={`securityCode-${i}`}
                                        className="inputClass h-14"
                                    />
                                ))}
                            </div>
                        </div>

                        {/* Verify Button */}
                        <button
                            type="submit"
                            className="w-full py-2 btn transition"
                        >
                            Verify My Account
                        </button>
                    </form>

                    {/* Resend */}
                    <p className="text-center text-sm">
                        Didn't get the code?{" "}
                        <Link to="/resend" className="linkClass">
                            Resend
                        </Link>
                    </p>
                </div>
            </div>
        </div>
    );
};

export default TwoSteps;