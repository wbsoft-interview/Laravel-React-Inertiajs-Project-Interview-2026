import { Link, useNavigate } from "react-router";
import Logo from "../../../assets/logo/wbLogo.png";
import TwoStepImg from "../../../assets/Auth/TwoSteps.png";
import { useEffect, useRef, useState } from "react";
import UseAxiosPublic from "../../../Hooks/useAxiosPublic";
import { toast } from "react-toastify";

const OtpVerify = () => {
    const axiosPublic = UseAxiosPublic();
    const navigate = useNavigate();
    const [loading, setLoading] = useState(false);
    const [otp, setOtp] = useState(["", "", "", "", "", ""]);
    const inputRefs = useRef([]);

    useEffect(() => {
        inputRefs.current?.[0]?.focus();
    }, []);

    const focusIndex = (i) => {
        inputRefs.current?.[i]?.focus();
        inputRefs.current?.[i]?.select?.();
    };

    const handleChange = (index, e) => {
        const raw = e.target.value;
        const digit = raw.replace(/\D/g, "");

        if (!digit) {
            const next = [...otp];
            next[index] = "";
            setOtp(next);
            return;
        }

        const digits = digit.split("");
        const next = [...otp];
        let i = index;

        for (const d of digits) {
            if (i > 5) break;
            next[i] = d;
            i++;
        }
        setOtp(next);
        const nextIndex = Math.min(index + digits.length, 5);
        if (index + digits.length <= 5) {
            focusIndex(nextIndex);
        } else {
            focusIndex(5);
        }
    };

    const handleKeyDown = (index, e) => {
        if (e.key === "Backspace") {
            if (otp[index]) {
                const next = [...otp];
                next[index] = "";
                setOtp(next);
                return;
            }

            if (index > 0) {
                focusIndex(index - 1);
                const next = [...otp];
                next[index - 1] = "";
                setOtp(next);
            }
        }

        if (e.key === "ArrowLeft" && index > 0) focusIndex(index - 1);
        if (e.key === "ArrowRight" && index < 5) focusIndex(index + 1);
    };

    const handlePaste = (index, e) => {
        e.preventDefault();
        const text = (e.clipboardData?.getData("text") || "").replace(/\D/g, "");
        if (!text) return;

        const digits = text.slice(0, 6).split("");
        const next = [...otp];
        let i = index;
        for (const d of digits) {
            if (i > 5) break;
            next[i] = d;
            i++;
        }

        setOtp(next);
        const last = Math.min(index + digits.length - 1, 5);
        focusIndex(last);
    };

    const handleOtpVerify = async (e) => {
        e.preventDefault();
        setLoading(true);

        const code = otp.join("");

        if (code.length !== 6 || !/^\d{6}$/.test(code)) {
            toast.error("Please enter the full 6-digit code.");
            setLoading(false);
            return;
        }
        const otpCode = Number(code);

        const mobile = sessionStorage.getItem("mobile");

        if (!mobile) {
            navigate("/register");
            setLoading(false);
            return;
        }

        const info = { mobile, verify_code: otpCode };
        
    };

    const handleResendOtp = async () => {
        setLoading(true);

        const mobile = sessionStorage.getItem("mobile");
        if (!mobile) {
            navigate("/register");
            setLoading(false);
            return;
        }

        try {
            const res = await axiosPublic.post("/api/resend-otp", { mobile });

            if (res?.data) {
                toast.success(res?.data?.message || "OTP sent again");
            }
        } catch (error) {
            const message =
                error?.response?.data?.errors?.[0] ||
                error?.response?.data?.message ||
                "Failed to resend OTP!";
            toast.error(message);
        } finally {
            setLoading(false);
        }
    };

    return (
        <div className="flex items-center bg-base-100">
            {/* Left Illustration */}
            <div className="hidden lg:flex lg:flex-col items-center justify-center relative lg:w-8/12">
                <img
                    src={TwoStepImg}
                    alt="OTP"
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
                        <h2 className="auth_headertext">OTP Verification 💬</h2>
                        <p className="text-primary-content">
                            We sent a verification code to your mobile. Enter the code from
                            the mobile in the field below.
                        </p>
                    </div>

                    {/* Form */}
                    <form onSubmit={handleOtpVerify} className="space-y-5">
                        {/* Code Inputs */}
                        <div>
                            <label className="labelClass">
                                Type your 6 digit security code
                            </label>

                            <div className="grid grid-cols-6 gap-2">
                                {otp.map((val, i) => (
                                    <input
                                        key={i}
                                        ref={(el) => (inputRefs.current[i] = el)}
                                        type="text"
                                        inputMode="numeric"
                                        autoComplete="one-time-code"
                                        maxLength={1}
                                        value={val}
                                        onChange={(e) => handleChange(i, e)}
                                        onKeyDown={(e) => handleKeyDown(i, e)}
                                        onPaste={(e) => handlePaste(i, e)}
                                        className="h-14 w-full border-2 border-primary rounded-2xl outline-none text-center text-4xl"
                                    />
                                ))}
                            </div>
                        </div>

                        {/* Verify Button */}
                        <button
                            type="submit"
                            disabled={loading}
                            className="w-full py-2 btn transition disabled:opacity-60"
                        >
                            {loading ? "Verifying..." : "Verify My Account"}
                        </button>
                    </form>

                    {/* Resend */}
                    <p className="text-center text-sm">
                        Didn't get the code?{" "}
                        <button
                            type="button"
                            onClick={handleResendOtp}
                            disabled={loading}
                            className="linkClass disabled:opacity-60"
                        >
                            Resend
                        </button>
                    </p>
                </div>
            </div>
        </div>
    );
};

export default OtpVerify;