import Lottie from "lottie-react";
import { Link } from "react-router";
import groovyWalkAnimation from "../../assets/json/groovyWalkAnimation.json";

const ErrorPage = () => {
    return (
        <div className="bg-gray-100 flex items-center justify-center min-h-screen p-6">
            <div className="text-center">
                <Lottie animationData={groovyWalkAnimation} loop={true} />
                <h2 className="text-2xl md:text-3xl font-semibold text-gray-800 mt-4">Page Not Found</h2>
                <p className="text-gray-600 mt-2">Sorry, the page you are looking for doesn’t exist or has been moved.</p>
                <Link to="/" className="mt-6 inline-block px-6 py-3 bg-blue-600 text-primary-content font-medium text-lg rounded hover:bg-blue-700 transition">Go Home</Link>
            </div>
        </div>
    );
};

export default ErrorPage;