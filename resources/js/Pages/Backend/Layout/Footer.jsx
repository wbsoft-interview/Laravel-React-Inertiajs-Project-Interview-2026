import React from "react";

const Footer = ({ footerText }) => {
    return (
        <div className="footer_text my-3">
            <div className="container-fluid">
                <div className="footer d-md-flex align-items-center justify-content-between">
                    <p className="mb-0">
                        <em>
                            {footerText}
                            <a href="#" className="text-primary"></a>
                        </em>
                    </p>
                    <p className="mb-0">
                        <em>
                            Developed By{" "}
                            <a href="http://wbsoftwares.com/">WB Softwares</a>
                        </em>
                    </p>
                </div>
            </div>
        </div>
    );
};

export default Footer;
