import { useState } from 'react';
import { FaEye, FaEyeSlash, FaFacebook, FaGithub, FaGoogle, FaTwitter } from 'react-icons/fa';
import { Link } from 'react-router';
import Logo from "../../../assets/logo/wbLogo.png";
import RegisterImg from "../../../assets/Auth/Register.png";
import RegisterImgDark from "../../../assets/Auth/Register_dark.png";
import { toast } from 'react-toastify';
import UseAuth from '../../../Hooks/UseAuth';
import { Translations } from '../../../utils/Translations';

const Register = () => {
    const { language } = UseAuth();
    const t = Translations[language];
    const [step, setStep] = useState(1);
    const [showPassword, setShowPassword] = useState(false);
    const [showConfirmationPassword, setShowConfirmationPassword] = useState(false);
    const [loading, setLoading] = useState(false);

    const handleRegister = async (e) => {
        e.preventDefault();
        setLoading(true);

        const form = e.target;
        const info = {
            name: form.name.value,
            institute_name_english: form.institute_name_english.value,
            email: form.email.value,
            unique_mobile: form.mobile.value,
            password: form.password.value,
            password_confirmation: form.password_confirmation.value,
        };

        if (!/^\d{11}$/.test(info.unique_mobile)) {
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
                    src={"light" === "light" ? RegisterImg : RegisterImgDark}
                    alt="Register"
                    className="max-w-lg 2xl:max-w-xl h-auto object-cover"
                />
            </div>

            {/* Right Card */}
            <div className="h-screen overflow-y-auto flex justify-center bg-base-200 p-10 w-full lg:w-4/12">
                <div className="w-full space-y-6">
                    <Link to="/">
                        <img className="w-fit h-10 mx-auto mb-4" src={Logo} alt="Logo" />
                    </Link>

                    <div>
                        <h2 className="auth_headertext">{language === "en" ? "Adventure starts here" : "অ্যাডভেঞ্চার এখান থেকেই শুরু হয়"} 🚀</h2>
                        <p className="text-primary-content">
                            {language === "en" ? "Make your result management easy and fun!" : "আপনার রেজাল্ট ম্যানেজমেন্টকে সহজ এবং মজাদার করুন!"}
                        </p>
                    </div>

                    {/* Step Indicator */}
                    <div className="relative flex items-center justify-between mb-6">
                        {/* Progress Line */}
                        <div className="absolute top-1/2 left-0 w-full h-1 bg-accent/40 -translate-y-1/2 rounded">
                            <div
                                className="h-1 bg-primary rounded transition-all duration-500"
                                style={{ width: `${(step - 1) * 50}%` }}
                            />
                        </div>

                        {/* Step 1 */}
                        <div className="relative z-10 flex flex-col items-center gap-1">
                            <div
                                className={`w-9 h-9 flex items-center justify-center rounded-full font-bold transition-all
                ${step >= 1
                                        ? "bg-primary text-primary-content shadow-lg scale-105"
                                        : "bg-accent text-primary-content"
                                    }`}
                            >
                                1
                            </div>
                            <span className={`text-xs ${step >= 1 ? "text-primary font-semibold" : "text-primary-content"}`}>
                                {language === "en" ? "Account" : "অ্যাকাউন্ট"}
                            </span>
                        </div>

                        {/* Step 2 */}
                        <div className="relative z-10 flex flex-col items-center gap-1">
                            <div
                                className={`w-9 h-9 flex items-center justify-center rounded-full font-bold transition-all
                ${step >= 2
                                        ? "bg-primary text-primary-content shadow-lg scale-105"
                                        : "bg-accent text-primary-content"
                                    }`}
                            >
                                2
                            </div>
                            <span className={`text-xs ${step >= 2 ? "text-primary font-semibold" : "text-primary-content"}`}>
                                {language === "en" ? "Security" : "নিরাপত্তা"}
                            </span>
                        </div>

                        {/* Step 3 */}
                        <div className="relative z-10 flex flex-col items-center gap-1">
                            <div
                                className={`w-9 h-9 flex items-center justify-center rounded-full font-bold transition-all
                ${step >= 3
                                        ? "bg-primary text-primary-content shadow-lg scale-105"
                                        : "bg-accent text-primary-content"
                                    }`}
                            >
                                3
                            </div>
                            <span className={`text-xs ${step >= 3 ? "text-primary font-semibold" : "text-primary-content"}`}>
                                {language === "en" ? "Finish" : "সমাপ্তি"}
                            </span>
                        </div>
                    </div>


                    <form onSubmit={handleRegister} className="space-y-5">
                        {/* ================= STEP 1 ================= */}
                        {step === 1 && (
                            <>
                                <div>
                                    <label className="labelClass">{t.name} <span className='text-red-500'>*</span></label>
                                    <input name="name" required className="inputClass" />
                                </div>

                                <div>
                                    <label className="labelClass">{t.email} ({t.optional})</label>
                                    <input type="email" name="email" className="inputClass" />
                                </div>

                                <div>
                                    <label className="labelClass">{t.mobile} <span className='text-red-500'>*</span></label>
                                    <input name="mobile" required placeholder="01*********" className="inputClass" />
                                </div>

                                <button
                                    type="button"
                                    className="w-full py-2 btn"
                                    onClick={() => setStep(2)}
                                >
                                    {t.next}
                                </button>
                            </>
                        )}

                        {/* ================= STEP 2 ================= */}
                        {step === 2 && (
                            <>
                                <div>
                                    <label className="labelClass">{t.password} <span className='text-red-500'>*</span></label>
                                    <div className="relative">
                                        <input
                                            type={showPassword ? "text" : "password"}
                                            name="password"
                                            required
                                            className="inputClass"
                                        />
                                        <button
                                            type="button"
                                            onClick={() => setShowPassword(!showPassword)}
                                            className="absolute top-1/2 right-4 -translate-y-1/3"
                                        >
                                            {showPassword ? <FaEyeSlash /> : <FaEye />}
                                        </button>
                                    </div>
                                </div>

                                <div>
                                    <label className="labelClass">{t.confirm} {t.password} <span className='text-red-500'>*</span></label>
                                    <div className="relative">
                                        <input
                                            type={showConfirmationPassword ? "text" : "password"}
                                            name="password_confirmation"
                                            required
                                            className="inputClass"
                                        />
                                        <button
                                            type="button"
                                            onClick={() =>
                                                setShowConfirmationPassword(!showConfirmationPassword)
                                            }
                                            className="absolute top-1/2 right-4 -translate-y-1/3"
                                        >
                                            {showConfirmationPassword ? <FaEyeSlash /> : <FaEye />}
                                        </button>
                                    </div>
                                </div>

                                <div className="grid grid-cols-2 gap-3">
                                    <button
                                        type="button"
                                        className="w-full py-2 btn"
                                        onClick={() => setStep(1)}
                                    >
                                        {t.back}
                                    </button>
                                    <button
                                        type="button"
                                        className="w-full py-2 btn"
                                        onClick={() => setStep(3)}
                                    >
                                        {t.next}
                                    </button>
                                </div>
                            </>
                        )}

                        {/* ================= STEP 3 ================= */}
                        {step === 3 && (
                            <>
                                <label className="flex items-center gap-2 text-sm">
                                    <input className="checkbox bg-base-200" type="checkbox" required />
                                    {language === "en" ? "I agree to" : " আমি সম্মত "}
                                    <Link to="/privacy-policy" className="linkClass">
                                        {language === "en" ? "privacy policy & terms" : "গোপনীয়তা নীতি ও শর্তাবলী"}
                                    </Link>
                                </label>

                                <div className="flex gap-3">
                                    <button
                                        type="button"
                                        className="w-1/2 py-2 btn"
                                        onClick={() => setStep(2)}
                                    >
                                        {t.back}
                                    </button>
                                    <button
                                        type="submit"
                                        disabled={loading}
                                        className="w-1/2 py-2 btn"
                                    >
                                        {t.register}
                                    </button>
                                </div>
                            </>
                        )}
                    </form>

                    <p className="text-center text-sm">
                        {language === "en" ? "Already have an account? " : "ইতিমধ্যে একটি অ্যাকাউন্ট আছে? "}
                        <Link to="../login" className="linkClass">
                            {t.login}
                        </Link>
                    </p>

                    <div className="flex items-center">
                        <hr className="grow border-accent" />
                        <span className="mx-4 text-primary">{t.or}</span>
                        <hr className="grow border-accent" />
                    </div>

                    <div className="flex justify-center gap-4 pb-10 text-xl">
                        <FaFacebook />
                        <FaTwitter />
                        <FaGithub />
                        <FaGoogle />
                    </div>
                </div>
            </div>
        </div>
    );
};

export default Register;
