import { Link } from 'react-router';
import Logo from "../../../assets/logo/logo.png";
import VerifyEmailImg from "../../../assets/Auth/handleResennd.png";

const VerifyEmail = () => {

    const handleVerifyEmail = (e) => {
        e.preventDefault();
        // Handle verify email logic here
        console.log("Verify Email clicked");
    };

    const handleResennd = (e) => {
        e.preventDefault();
        // Handle resend email logic here
        console.log("Resend clicked");
    };
    return (
        <div className="flex items-center bg-base-100">
            {/* Left Illustration */}
            <div className="hidden lg:flex lg:flex-col items-center justify-center  relative lg:w-8/12">
                <img
                    src={VerifyEmailImg}
                    alt="Login image"
                    className="max-w-sm xl:max-w-md h-auto object-cover"
                />
            </div>

            {/* Right Login Card */}
            <div className="h-screen flex items-center justify-center bg-base-200  p-10 w-full lg:w-4/12">

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
                        <h2 className="auth_headertext ">
                            Verify your email ✉️
                        </h2>
                        <p className="text-primary-content">
                            Account activation link sent to your email address: hello@example.com Please follow the link inside to continue.
                        </p>
                    </div>

                    <button
                        onClick={handleVerifyEmail}
                        className="w-full py-2 btn transition"
                    >
                        Skip For Now
                    </button>

                    {/* Create Account */}
                    <p className="text-center text-sm">
                        Didn't get the mail?{" "}
                        <button onClick={handleResennd} className="linkClass">
                            Resend
                        </button>
                    </p>

                </div>
            </div>
        </div>
    );
};

export default VerifyEmail;