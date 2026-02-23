import { Outlet } from 'react-router';

const AuthenticationLayout = () => {
    return (
        <div
            className=" h-[calc(100vh-52px)] bg-base-100 print:bg-base-200"
        >
            <div className="relative">
                <Outlet />
            </div>
        </div>
    );
};

export default AuthenticationLayout;
