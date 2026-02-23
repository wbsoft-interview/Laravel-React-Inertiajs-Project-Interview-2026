import { toast } from "react-toastify";
import useUser from "./useUser";
import { useNavigate } from "react-router";

const CheckAdmin = ({ children }) => {
    const navigate = useNavigate();
    const token = localStorage.getItem("token");
    const user = useUser();

    const access = user?.role === "admin";

    if (!token) {
        return (
            toast.error("You are not Login") && navigate("/login")
        );
    } else if (!access) {
        return (
            toast.error("You are not Admin") && navigate("/")
        );
    } else {
        return children;
    }
};

export default CheckAdmin;
