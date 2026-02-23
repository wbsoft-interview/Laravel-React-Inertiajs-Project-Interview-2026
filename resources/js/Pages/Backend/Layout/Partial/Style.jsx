import React from "react";

const Style = () => {
    return (
        <div>
            <link
                rel="stylesheet"
                href="/backend/template-assets/css/bootstrap.min.css"
            />
            <link
                rel="stylesheet"
                href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
                integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
                crossOrigin="anonymous"
                referrerPolicy="no-referrer"
            />
            <link rel="preconnect" href="https://fonts.googleapis.com" />
            <link
                rel="preconnect"
                href="https://fonts.gstatic.com"
                crossOrigin
            />
            <link
                href="https://fonts.googleapis.com/css2?family=Montserrat:wght@200;400;500;600;700&family=Raleway:wght@300&family=Roboto:ital,wght@0,100;1,900&family=Yuji+Hentaigana+Akari&display=swap"
                rel="stylesheet"
            />
            <link
                rel="stylesheet"
                href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0"
            />
            <link
                rel="stylesheet"
                href="https://fonts.googleapis.com/icon?family=Material+Icons"
            />
            <link
                rel="stylesheet"
                href="//cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css"
            />
            <link
                href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css"
                rel="stylesheet"
            />
            <link
                rel="stylesheet"
                href="/backend/template-assets/css/style.css"
            />
            <link
                rel="stylesheet"
                href="/backend/custom/css/flatpickr.min.css"
            />
            <link rel="stylesheet" href="/frontend/css/toastr.min.css" />
            <link
                href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css"
                rel="stylesheet"
            />
            <link rel="stylesheet" href="/backend/custom/css/custom.css" />

            {/* Inline custom styles */}
            <style>{`
                .modal input {
                    padding-top: 7px;
                    padding-bottom: 7px;
                }
                .custom-time-picker{
                    padding-top: 18px !important;
                    padding-bottom: 19px !important;
                }
                #content .main-content{
                    overflow-x: unset;
                }
            `}</style>
        </div>
    );
};

export default Style;
