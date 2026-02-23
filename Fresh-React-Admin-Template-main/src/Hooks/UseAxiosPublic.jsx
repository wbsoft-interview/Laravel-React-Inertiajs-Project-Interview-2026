import axios from "axios";
import { url } from "../../connection";

const axiosInstance = axios.create({
    baseURL: `${url}`,
});

const UseAxiosPublic = () => {
    return axiosInstance;
}

export default UseAxiosPublic;