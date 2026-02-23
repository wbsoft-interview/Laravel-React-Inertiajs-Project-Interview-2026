import React, { useEffect, useState } from "react";
import { Link, useForm, usePage } from "@inertiajs/inertia-react";
import { Inertia } from "@inertiajs/inertia";
import Swal from "sweetalert2";
import AppLayout from "../AppLayout";

const Index = () => {
    const { auth, flash, errors: inertiaErrors, accountData, allAccountCount } = usePage().props;
    const user = auth.user;
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

    //To update profile basic...
    const form = useForm({
        account_name: "",
        account_holder_name: "",
        account_number: "",
        account_balance: 0,
    });

    const submitFormData = (e) => {
        e.preventDefault();

        form.post(`/account`, {
            forceFormData: true,
            preserveState: true,
            preserveScroll: true,

            onSuccess: () => {
                form.reset();
                document.getElementById("addAccountClose")?.click();
            },

            onError: (errors) => {
                console.log("Validation Failed:", errors);
            },
        });
    };

    const handleDelete = (id) => {
        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!",
        }).then((result) => {
            if (result.isConfirmed) {
                Inertia.get(
                    `/account-delete/${id}`,
                    {},
                    {
                        preserveScroll: true,
                        onSuccess: () => {
                            Swal.fire(
                                "Deleted!",
                                "The account has been deleted.",
                                "success"
                            );
                        },
                        onError: () => {
                            Swal.fire(
                                "Error",
                                "Something went wrong.",
                                "error"
                            );
                        },
                    }
                );
            }
        });
    };

    // Handle Delete Category
    const handleDelete12 = async (id) => {
        if (confirm("Are you sure you want to delete?")) {
            await fetch(`/account-delete/${id}`, {
                method: "GET",
                headers: {
                    "X-Requested-With": "XMLHttpRequest",
                },
            });

            Swal.fire("Deleted!", "The account has been deleted.", "success");
        }
    };


    return (
        <AppLayout>
            <div className="row py-3 ps-2">
                <h3 className="mb-0">Account List</h3>
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
                                        All ({allAccountCount})
                                    </Link>
                                </p>
                            </div>
                            <div className="d-sm-block">
                                {can["account-create"] && (
                                    <button
                                        className="btn btn-success rounded-0 d-flex gap-1 align-items-center px-2 border-0"
                                        data-bs-toggle="modal"
                                        data-bs-target="#addAccount"
                                    >
                                        <i className="fa fa-plus me-1"></i>
                                        New Account
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
                                <th>Serial</th>
                                <th>Details</th>
                                <th>Account</th>
                                <th>Status</th>
                                <th>Date/Time</th>
                            </tr>
                        </thead>

                        <tbody className="text-center">
                            {accountData.data.map((item, index) => (
                                
                                <tr key={item.id}>
                                    <td>
                                        <b>#{index + 1}</b>
                                    </td>

                                    <td className="text-start">
                                        <div className="row_title">
                                            <b>Holder:</b>{" "}
                                            {item.account_holder_name}
                                            <br />
                                            <b>Account:</b> {item.account_name}
                                        </div>
                                        <div className="row-actions mt-2">
                                            {can["account-edit"] && (
                                                <>
                                                    <Link
                                                        href={`/account-profile/${item.id}`}
                                                        className="text-primary fw-bold"
                                                    >
                                                        Details
                                                    </Link>
                                                    {/* {" | "}
                                                    <Link
                                                        href={`/account-profile/${item.id}`}
                                                        className="text-info fw-bold"
                                                    >
                                                        Edit
                                                    </Link> */}

                                                    {item.status == true ? (
                                                        <span>
                                                            {" | "}
                                                            <Link
                                                                href={`/account-inactive/${item.id}`}
                                                                className="text-warning fw-bolder"
                                                            >
                                                                Not Default
                                                            </Link>
                                                        </span>
                                                    ) : (
                                                        <span>
                                                            {" | "}
                                                            <Link
                                                                href={`/account-active/${item.id}`}
                                                                className="text-success fw-bolder"
                                                            >
                                                                Default
                                                            </Link>
                                                        </span>
                                                    )}
                                                </>
                                            )}
                                            {can["account-delete"] && (
                                                <>
                                                    {" | "}
                                                    <button
                                                        onClick={() => handleDelete(item.id)}
                                                        className="text-danger fw-bold border-0 bg-transparent row-delete"
                                                    >
                                                        Delete
                                                    </button>
                                                </>
                                            )}
                                        </div>
                                    </td>

                                    <td className="text-start">
                                        <b>Number:</b> {item.account_number}
                                        <br />
                                        <b>Balance:</b> {item.account_balance}{" "}
                                        tk
                                    </td>

                                    <td>
                                        {item.status ? (
                                            <span className="badge bg-success">
                                                Default
                                            </span>
                                        ) : (
                                            <span className="badge bg-danger">
                                                Not Default
                                            </span>
                                        )}
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

                <div className="d-flex justify-content-end pb-2">
                    {accountData.links.map((link, i) => (
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

            {/* ---------------- ADD MODAL ---------------- */}
            <div
                className="modal fade"
                id="addAccount"
                tabIndex="-1"
                data-bs-backdrop="static"
            >
                <div className="modal-dialog modal-dialog-centered max-width-900px">
                    <div className="modal-content">
                        <form onSubmit={submitFormData}>
                            <div className="modal-header">
                                <h5>New Account</h5>
                                <button
                                    type="button"
                                    id="addAccountClose"
                                    className="btn-close"
                                    data-bs-dismiss="modal"
                                />
                            </div>

                            <div className="modal-body p-0">
                                <div className="row px-4 my-4">
                                    <div className="col-md-6 mb-3">
                                        <div className="form-group">
                                            <label htmlFor="account_name">
                                                Account Name{" "}
                                                <span className="text-danger">
                                                    *
                                                </span>
                                            </label>
                                            <input
                                                type="text"
                                                name="account_name"
                                                required
                                                className="form-control form-control-solid"
                                                placeholder="Name"
                                                value={form.data.account_name}
                                                onChange={(e) =>
                                                    form.setData(
                                                        "account_name",
                                                        e.target.value
                                                    )
                                                }
                                            />
                                        </div>

                                        {form.errors.account_name && (
                                            <span className="text-danger">
                                                {form.errors.account_name}
                                            </span>
                                        )}
                                    </div>

                                    <div className="col-md-6 mb-3">
                                        <div className="form-group">
                                            <label htmlFor="account_holder_name">
                                                Account Holder Name{" "}
                                                <span className="text-danger">
                                                    *
                                                </span>
                                            </label>
                                            <input
                                                type="text"
                                                name="account_holder_name"
                                                required
                                                className="form-control form-control-solid"
                                                placeholder="Holder Name"
                                                value={
                                                    form.data
                                                        .account_holder_name
                                                }
                                                onChange={(e) =>
                                                    form.setData(
                                                        "account_holder_name",
                                                        e.target.value
                                                    )
                                                }
                                            />
                                        </div>

                                        {form.errors.account_holder_name && (
                                            <span className="text-danger">
                                                {
                                                    form.errors
                                                        .account_holder_name
                                                }
                                            </span>
                                        )}
                                    </div>

                                    <div className="col-md-6 mb-3">
                                        <div className="form-group">
                                            <label htmlFor="account_number">
                                                Account Number{" "}
                                                <span className="text-danger">
                                                    *
                                                </span>
                                            </label>
                                            <input
                                                type="number"
                                                name="account_number"
                                                required
                                                className="form-control form-control-solid"
                                                placeholder="Account Number"
                                                value={form.data.account_number}
                                                onChange={(e) =>
                                                    form.setData(
                                                        "account_number",
                                                        e.target.value
                                                    )
                                                }
                                            />
                                        </div>

                                        {form.errors.account_number && (
                                            <span className="text-danger">
                                                {form.errors.account_number}
                                            </span>
                                        )}
                                    </div>

                                    <div className="col-md-6 mb-3">
                                        <div className="form-group">
                                            <label htmlFor="account_balance">
                                                Account Balance{" "}
                                                <span className="text-danger">
                                                    *
                                                </span>
                                            </label>
                                            <input
                                                type="number"
                                                name="account_balance"
                                                required
                                                className="form-control form-control-solid"
                                                placeholder="Account Balance"
                                                value={
                                                    form.data.account_balance
                                                }
                                                onChange={(e) =>
                                                    form.setData(
                                                        "account_balance",
                                                        e.target.value
                                                    )
                                                }
                                            />
                                        </div>

                                        {form.errors.account_balance && (
                                            <span className="text-danger">
                                                {form.errors.account_balance}
                                            </span>
                                        )}
                                    </div>
                                </div>
                            </div>

                            <div className="modal-footer">
                                <button
                                    className="btn btn-success"
                                    disabled={form.processing}
                                >
                                    Save
                                </button>
                                <button
                                    type="button"
                                    className="btn btn-danger"
                                    data-bs-dismiss="modal"
                                >
                                    Cancel
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </AppLayout>
    );
};

export default Index;
