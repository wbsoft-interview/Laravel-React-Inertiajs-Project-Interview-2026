import axios from "axios";
import { url } from "../../connection";

const UseAxiosSecure = () => {
    const axiosSecure = axios.create({
        baseURL: url,
    });

    axiosSecure.interceptors.request.use((config) => {
        const token = localStorage.getItem("token");
        const tokenType = localStorage.getItem("tokenType") || "Bearer";

        if (token) {
            config.headers.Authorization = `${tokenType} ${token}`;
        }
        return config;
    });

    return axiosSecure;
};

export default UseAxiosSecure;