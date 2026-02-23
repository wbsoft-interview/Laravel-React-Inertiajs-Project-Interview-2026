import React from "react";

const Script = ({ pageProps }) => {
    // Extract errors from the page props
    const errors = pageProps?.errors || [];

    return (
        <div>
            {/* External JS files */}
            <script
                src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"
                integrity="sha512-3gJwYpMe3QewGELv8k/BX9vcqhryRdzRMxVfq6ngyWXwo03GFEzjsUm8Q7RZcHPHksttq7/GFoxjCVUjkjvPdw=="
                crossOrigin="anonymous"
                referrerPolicy="no-referrer"
            ></script>
            <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
            <script src="/backend/template-assets/js/bootstrap.min.js"></script>
            <script src="//cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
            <script src="/backend/custom/js/flatpickr.min.js"></script>
            <script src="/backend/template-assets/js/scritp.js"></script>
            <script src="/backend/image-preview/js/lightcase.js"></script>
            <script src="/backend/image-preview/js/swiper.min.js"></script>
            <script src="/backend/image-preview/js/progress.js"></script>
            <script src="/backend/image-preview/js/functions.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
            <script src="/backend/custom/js/toastr.min.js"></script>

            {/* Inline toastr validation errors */}
            <script
                dangerouslySetInnerHTML={{
                    __html: errors
                        .map(
                            (err) =>
                                `toastr.error(${JSON.stringify(
                                    err
                                )}, "Validation Error", { "progressBar": true });`
                        )
                        .join("\n"),
                }}
            />
        </div>
    );
};

export default Script;
