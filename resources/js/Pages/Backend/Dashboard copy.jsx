import React from "react";
import AppLayout from "./AppLayout";

const Dashboard = ({ logo, userCount, canUserList }) => {
    return (
        <AppLayout>
            {/* Styles (from Blade) */}
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
                    {/* Notice Card */}
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

                    {/* User Info Card */}
                    <div className="col-lg-6 col-md-6 col-sm-6 mb-4">
                        <div className="h-100">
                            <div className="card card-stats h-100">
                                <div className="card-header">
                                    <div className="icon icon-warning d-flex">
                                        <span className="material-symbols-outlined">
                                            campaign
                                        </span>
                                        <p className="category custom-card-header-title">
                                            <strong>ব্যাবহারকারী</strong>
                                        </p>
                                    </div>
                                </div>
                                <div className="card-content px-3 py-2">
                                    <p className="category mb-0">
                                        এই সফটওয়্যারটি Tiayra Laser & Aesthetic
                                        Center এর জন্য প্রস্তুতকৃত।
                                    </p>
                                    <p className="mb-0">
                                        ক্লায়েন্ট - Unlimited
                                    </p>
                                    <p className="mb-0">সার্ভিস - Unlimited</p>
                                    <p className="mb-0">ইনভয়েস - Unlimited</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {/* Users Card (Conditional) */}
                    {canUserList && (
                        <div className="col-lg-4 col-md-4 col-sm-6 mb-4">
                            <div className="h-100">
                                <div className="card card-stats h-100">
                                    <div className="card-header">
                                        <div className="icon icon-warning d-flex">
                                            <span className="material-symbols-outlined">
                                                equalizer
                                            </span>
                                            <p className="category custom-card-header-title">
                                                <strong>Users</strong>
                                            </p>
                                        </div>
                                    </div>
                                    <div className="card-content ps-3 py-2">
                                        <p className="category">
                                            <strong>Total</strong>
                                        </p>
                                        <h3 className="card-title">
                                            {userCount}
                                        </h3>
                                    </div>
                                    <div className="card-footer p-0 dashboard-card-footer">
                                        <div className="stats">
                                            <div className="row">
                                                <a
                                                    href="/users"
                                                    className="text-white"
                                                >
                                                    <div className="col-12 dashboard-card-color text-center py-2">
                                                        User List
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    )}
                </div>
            </div>
        </AppLayout>
    );
};

export default Dashboard;
