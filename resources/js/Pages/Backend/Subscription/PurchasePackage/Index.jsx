import React from "react";
import { Link, usePage } from "@inertiajs/inertia-react";
import AppLayout from "../../AppLayout";

const Index = () => {
    const { auth, adminPackageData, allAdminPackageCount } = usePage().props;
    const can = auth?.can || {};

    // Helper function for status badges
    const getStatusBadge = (status) => {
        const lowerStatus = status?.toLowerCase();
        switch (lowerStatus) {
            case 'active': return 'bg-success';
            case 'expired': return 'bg-danger';
            case 'upgraded': return 'bg-warning';
            default: return 'bg-secondary';
        }
    };

    return (
        <AppLayout>
            <div className="row py-3 ps-2">
                <h3 className="mb-0">Purchase Accounts</h3>
            </div>

            <div className="table_wrapper py-1 card">
                <div className="row my-2 aggregate-section-div">
                    <div className="px-3 ">
                        <div className="d-flex justify-content-between align-items-center aggregate-section border">
                            <div className="d-flex align-items-center">
                                <p className="mb-0">
                                    <Link
                                        href="/purchase-account-list"
                                        className="text-primary py-2 px-3 active"
                                    >
                                        All ({allAdminPackageCount})
                                    </Link>
                                </p>
                            </div>
                            <div className="d-sm-block">
                                {can["package-create"] && (
                                    <button className="btn btn-success rounded-0 d-flex gap-1 align-items-center px-2 border-0">
                                        <i className="fa fa-list me-1"></i>
                                        Purchase List
                                    </button>
                                )}
                            </div>
                        </div>
                    </div>
                </div>

                <div className="table-responsive">
                    <table className="table table-bordered">
                        <thead className="text-uppercase text-center">
                            <tr>
                                <th className="text-center"><span>Serial</span></th>
                                <th className="text-center"><span>Account</span></th>
                                <th className="text-center"><span>Package Basic</span></th>
                                <th className="text-center"><span>Package Details</span></th>
                                <th className="text-center"><span>Status</span></th>
                                <th className="text-center"><span>Start/Date</span></th>
                            </tr>
                        </thead>

                        <tbody className="text-center">
                            {adminPackageData.data.map((item, index) => (
                                <tr key={item.id}>
                                    <td>
                                        <b>#{index + 1}</b>
                                    </td>
                                    <td className="text-start">
                                        <div className="row_title">
                                            <b>Name: </b> {item.package_by_data?.name} <br />
                                            <b>Mobile: </b> {item.package_by_data?.mobile} <br />
                                            <b>Email: </b> {item.package_by_data?.email} <br />
                                        </div>
                                        <div className="row-actions mt-2">
                                            <span>
                                                <Link className="text-primary fw-bolder"
                                                    href={`/purchase-account-profile/${item.id}`}>
                                                    Account Details
                                                </Link>
                                            </span>
                                        </div>
                                    </td>

                                    <td className="text-start">
                                        <b>Name: </b> {item.package_data?.package_name} <br />
                                        <b>Validity: </b> {item.package_data?.package_validity} (Days) <br />
                                        <b>Category: </b> {item.package_data?.package_category_data?.category_name}
                                    </td>

                                    <td className="text-start">
                                        <b>Price: </b>{item.package_data?.package_price} <br />
                                        <b>SMS Qty: </b>{item.package_data?.sms_qty > 0 ? `${item.package_data.sms_qty} qty` : '0 qty'} <br />
                                        <b>Student Qty: </b>{item.package_data?.student_qty > 0 ? `${item.package_data.student_qty} qty` : '0 qty'}
                                    </td>

                                    <td>
                                        <span className={`badge ${getStatusBadge(item.status)}`}>
                                            {item.status ? item.status.charAt(0).toUpperCase() + item.status.slice(1) : 'Unknown'}
                                        </span>
                                    </td>

                                    <td>
                                        {item.created_at ? (() => {
                                            const [datePart, timePart] = item.created_at.split(" ");
                                            const [day, month, year] = datePart.split("-");
                                            const [hour24, minute] = timePart.split(":");
                                            const hour = hour24 % 12 === 0 ? 12 : hour24 % 12;
                                            const ampm = hour24 >= 12 ? "pm" : "am";
                                            const formattedDate = `${day}-${month}-${year}`;
                                            const formattedTime = `${hour}:${minute} ${ampm}`;

                                            return (
                                            <>
                                                <div>{formattedDate}</div>
                                                <div>{formattedTime}</div>
                                            </>
                                            );
                                        })() : "No Date"}
                                    </td>
                                </tr>
                            ))}
                        </tbody>
                    </table>
                </div>

                <div className="d-flex justify-content-end pb-2 px-2">
                    {adminPackageData.links.map((link, i) => (
                        <Link
                            key={i}
                            href={link.url || "#"}
                            className={`btn btn-sm mx-1 ${link.active ? "btn-primary" : "btn-outline-primary"} ${!link.url ? "disabled" : ""}`}
                            dangerouslySetInnerHTML={{ __html: link.label }}
                        />
                    ))}
                </div>
            </div>
        </AppLayout>
    );
};

export default Index;