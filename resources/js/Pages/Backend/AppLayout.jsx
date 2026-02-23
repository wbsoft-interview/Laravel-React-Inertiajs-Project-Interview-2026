import React, { useEffect } from "react";
import { usePage } from "@inertiajs/inertia-react";
import Swal from "sweetalert2";
import Sidebar from "./Layout/Sidebar";
import Header from "./Layout/Header";
import Footer from "./Layout/Footer";

const AppLayout = ({ children, modal }) => {
    const { props } = usePage();

    useEffect(() => {
        if (props.flash?.success) {
            Swal.fire({
                icon: "success",
                title: "Success",
                text: props.flash.success,
                timer: 4000,
                showConfirmButton: false,
            });
        }
        if (props.flash?.error) {
            Swal.fire({
                icon: "error",
                title: "Error",
                text: props.flash.error,
                timer: 4000,
                showConfirmButton: false,
            });
        }
    }, [props.flash]);

    useEffect(() => {
        if (window.$) {
            window.$("#client_id_fh").select2({
                dropdownParent: window.$("#filterClient"),
            });
        }
    }, []);

    return (
        <div className="wrapper">

            <div className="body-overlay"></div>

            <Sidebar />

            <div id="content">
                <Header />

                <div className="main-content custom-master-main-content p-0">
                    {children}
                </div>

                <Footer />
            </div>
        </div>
    );
};

export default AppLayout;
