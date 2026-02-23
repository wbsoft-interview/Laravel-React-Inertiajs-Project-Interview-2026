import { Navigate, useLocation } from 'react-router';

const PublicRoute = ({ children }) => {
    const location = useLocation();
    const isLoggedIn = localStorage.getItem("token");

    if (!isLoggedIn) {
        return <Navigate to="/login" state={{ from: location }} replace />;
    }

    return children;
};

export default PublicRoute;