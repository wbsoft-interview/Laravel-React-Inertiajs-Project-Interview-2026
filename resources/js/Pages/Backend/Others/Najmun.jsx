import React from "react";
import AppLayout from "../AppLayout";

const Najmun = ({ logo, userCount, canUserList }) => {
    return (
        <AppLayout>
            <style>
                {`
                    .custom-card-header-title {
                        margin-top: 8px;
                        margin-bottom: 0px;
                    }
                `}
            </style>

            <div className="card mt-3 shadow">
                <div className="row px-3 py-3">
                    <div className="col-lg-6 col-md-6 col-sm-6 mb-4">
                        <div className="h-100">
                            <div className="card card-stats h-100">
                                <div className="card-header">
                                    <div className="icon icon-warning d-flex">
                                        <span className="material-symbols-outlined">
                                            campaign
                                        </span>
                                        <p className="category custom-card-header-title">
                                            <strong>নোটিশ</strong>
                                        </p>
                                    </div>
                                </div>
                                <div className="card-content px-3 py-2">
                                    <p className="category mb-0">
                                        প্রতিষ্ঠানের ডাটা সুরক্ষার কথা মাথায়
                                        রেখে আমরা সফটওয়্যারের ডিলিট অপশন বন্ধ
                                        রেখেছি, আমরা আপনাদের অনুরোধ করবো ডিলিট
                                        না করে ইনাক্টিভ করুন। কিন্তু
                                        প্রতিষ্ঠানের আবেদনের ভিত্তিতে ডিলিট
                                        অপশনটি পুনরায় চালু করা যেতে পারে।
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </AppLayout>
    );
};

export default Najmun;
