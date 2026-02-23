import React, { useEffect, useState } from "react";
import { Link, useForm, usePage } from "@inertiajs/inertia-react";
import { Inertia } from "@inertiajs/inertia";
import Swal from "sweetalert2";
import AppLayout from "../../AppLayout";

const Index = () => {
    const {
        auth,
        flash,
        errors: inertiaErrors,
        accountTransferData,
        allAccountTransferCount,
        singleAccountData,
    } = usePage().props;
    const can = auth?.can || {};
    useEffect(() => {
        if (flash?.success) Swal.fire("Success", flash.success, "success");
        if (flash?.error) Swal.fire("Error", flash.error, "error");

        const errorKeys = Object.keys(inertiaErrors);
        if (errorKeys.length > 0) {
            const firstErrorMessage = inertiaErrors[errorKeys[0]];
            Swal.fire("Error", firstErrorMessage, "error");
        }
    }, [flash, inertiaErrors]);

    return (
        <AppLayout>
            <div className="row py-3 ps-2">
                <h3 className="mb-0">Account Profile</h3>
            </div>

            <div className="card shadow">
                <div className="card-header">
                    <div className="d-flex justify-content-between align-items-center">
                        <h4 className="mb-0 pb-0">Account Details</h4>
                        <div className="d-sm-block"></div>
                    </div>
                </div>

                <div className="card-body">
                    <div className="table-container table-responsive">
                        <table className="table table-bordered">
                            <thead className="text-uppercase">
                                <tr>
                                    <th className="text-center">Account</th>
                                    <th className="text-center">Holder Name</th>
                                    <th className="text-center">
                                        Account Number
                                    </th>
                                    <th className="text-center">
                                        Main Balance
                                    </th>
                                    <th className="text-center">
                                        Opening Date
                                    </th>
                                </tr>
                            </thead>

                            <tbody className="text-center">
                                <tr>
                                    <td>
                                        <b>{singleAccountData.account_name}</b>
                                    </td>

                                    <td>
                                        {singleAccountData.account_holder_name}
                                    </td>

                                    <td>{singleAccountData.account_number}</td>

                                    <td>
                                        {singleAccountData.account_balance} Tk
                                    </td>

                                    <td>
                                        <span className="text-normal">
                                            {new Date(
                                                singleAccountData.created_at
                                            ).toLocaleDateString("en-GB")}
                                        </span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div className="row py-3 ps-2">
                <h4 className="mb-0">Transaction Details</h4>
            </div>

            <div className="table_wrapper py-1 card">
                <div className="row my-2 aggregate-section-div">
                    <div className="px-3 ">
                        <div className=" d-flex justify-content-between align-items-center aggregate-section border">
                            <div className="d-flex align-items-center">
                                <p className="mb-0">
                                    <Link
                                        href="/account"
                                        className="text-primary py-2 px-3 active"
                                    >
                                        All ({allAccountTransferCount})
                                    </Link>
                                </p>
                            </div>
                            <div className="d-sm-block">
                                {can["account-create"] && (
                                    <button className="btn btn-success rounded-0 d-flex gap-1 align-items-center px-2 border-0">
                                        <i className="fa fa-list me-1"></i>
                                        List
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
                                <th>Transaction Date</th>
                                <th>Purpose</th>
                                <th>Credit</th>
                                <th>Debit</th>
                                <th>Balance</th>
                                <th>By</th>
                                <th>Entry Date</th>
                            </tr>
                        </thead>

                        <tbody className="text-center">
                            {accountTransferData.data.map((item) => (
                                <tr key={item.id}>
                                    <td>
                                        <span className="fw-bolder">
                                            {new Date(
                                                item.transfer_date
                                            ).toLocaleDateString("en-GB")}
                                        </span>
                                    </td>

                                    <td className="text-start">
                                        <div className="row_title">
                                            {item.transfer_purpuse}
                                        </div>
                                    </td>

                                    <td>
                                        {item.transfer_type === "Credit" && (
                                            <span>
                                                {item.transfer_amount} Tk
                                            </span>
                                        )}
                                    </td>

                                    <td>
                                        {item.transfer_type === "Debit" && (
                                            <span>
                                                {item.transfer_amount} Tk
                                            </span>
                                        )}
                                    </td>

                                    <td>{item.current_amount} Tk</td>

                                    <td>{item.transfer_by_data?.name}</td>

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

                <div className="d-flex justify-content-end pb-2">
                    {accountTransferData.links.map((link, i) => (
                        <Link
                            key={i}
                            href={link.url || ""}
                            className={`btn btn-sm mx-1 ${
                                link.active
                                    ? "btn-primary"
                                    : "btn-outline-primary"
                            }`}
                            dangerouslySetInnerHTML={{
                                __html: link.label,
                            }}
                        />
                    ))}
                </div>
            </div>
        </AppLayout>
    );
};

export default Index;
