import React, { useEffect } from "react";
import { Link, useForm, usePage } from "@inertiajs/inertia-react";
import { Inertia } from "@inertiajs/inertia";
import Swal from "sweetalert2";
import AppLayout from "../../AppLayout";

const Index = () => {
    const {
        auth,
        flash,
        errors: inertiaErrors,
        packageCategoryData,
        allPackageCategoryCount,
    } = usePage().props;

    const can = auth?.can || {};

    useEffect(() => {
        if (flash?.success) Swal.fire("Success", flash.success, "success");
        if (flash?.error) Swal.fire("Error", flash.error, "error");

        const errorKeys = Object.keys(inertiaErrors || {});
        if (errorKeys.length > 0) {
            Swal.fire("Error", inertiaErrors[errorKeys[0]], "error");
        }
    }, [flash, inertiaErrors]);

    const form = useForm({
        category_name: "",
    });

    //To add new data...
    const submitFormData = (e) => {
        e.preventDefault();

        form.post("/package-category", {
            preserveScroll: true,
            onSuccess: () => {
                form.reset();
                document.getElementById("addCategoryClose")?.click();
            },
        });
    };
    
    //To update data...
    const submitFormDataFU = (e, id) => {
        e.preventDefault();

        form.put(`/package-category/${id}`, {
            preserveScroll: true,
            onSuccess: () => {
                document.getElementById(`updateCategoryClose${id}`)?.click();
                form.reset();
            },
        });
    };

    //To delete data...
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
                    `/package-category-delete/${id}`,
                    {},
                    { preserveScroll: true }
                );
            }
        });
    };

    return (
        <AppLayout>
            <div className="row py-3 ps-2">
                <h3 className="mb-0">Category List</h3>
            </div>

            <div className="table_wrapper py-1 card">
                <div className="row my-2 aggregate-section-div">
                    <div className="px-3">
                        <div className="d-flex justify-content-between align-items-center aggregate-section border">
                            <div className="d-flex align-items-center">
                                <p className="mb-0">
                                    <Link
                                        href="/package-category"
                                        className="text-primary py-2 px-3 active"
                                    >
                                        All ({allPackageCategoryCount})
                                    </Link>
                                </p>
                            </div>
                            <div className="d-sm-block">
                                {can["package-category-create"] && (
                                    <button
                                        className="btn btn-success rounded-0 d-flex gap-1 align-items-center px-2 border-0"
                                        data-bs-toggle="modal"
                                        data-bs-target="#addCategory"
                                    >
                                        <i className="fa fa-plus"></i>
                                        <span>New Category</span>
                                    </button>
                                )}
                            </div>
                        </div>
                    </div>
                </div>

                <div className="row px-3">
                    <div className="table-container table-responsive">
                        <table className="table table-bordered">
                            <thead className="text-uppercase">
                                <tr>
                                    <th className="text-center">Serial</th>
                                    <th className="text-center">
                                        Category Name
                                    </th>
                                    <th className="text-center">Status</th>
                                    <th className="text-center">Date/Time</th>
                                </tr>
                            </thead>

                            <tbody>
                                {packageCategoryData.data.map((item, index) => (
                                    <React.Fragment key={item.id}>
                                        <tr className="text-center">
                                            <td>
                                                <b>#{index + 1}</b>
                                            </td>

                                            <td className="text-start">
                                                <div className="row_title">
                                                    {item.category_name}
                                                </div>
                                                <div className="row-actions mt-2">
                                                    {can[
                                                        "package-category-edit"
                                                    ] && (
                                                        <>
                                                            <button
                                                                className="text-info fw-bolder border-0 bg-transparent"
                                                                data-bs-toggle="modal"
                                                                data-bs-target={`#updateCategory${item.id}`}
                                                                onClick={() =>
                                                                    form.setData(
                                                                        {
                                                                            category_name:
                                                                                item.category_name,
                                                                        }
                                                                    )
                                                                }
                                                            >
                                                                Edit
                                                            </button>

                                                            {item.status ? (
                                                                <span>
                                                                    {" | "}
                                                                    <Link
                                                                        className="text-warning fw-bolder"
                                                                        href={`/package-category-inactive/${item.id}`}
                                                                    >
                                                                        Inactive
                                                                    </Link>
                                                                </span>
                                                            ) : (
                                                                <span>
                                                                    {" | "}
                                                                    <Link
                                                                        className="text-success fw-bolder"
                                                                        href={`/package-category-active/${item.id}`}
                                                                    >
                                                                        Active
                                                                    </Link>
                                                                </span>
                                                            )}
                                                        </>
                                                    )}

                                                    {can[
                                                        "package-category-delete"
                                                    ] && (
                                                        <span>
                                                            {" | "}
                                                            <button
                                                                className="text-danger fw-bolder border-0 bg-transparent"
                                                                onClick={() =>
                                                                    handleDelete(
                                                                        item.id
                                                                    )
                                                                }
                                                            >
                                                                Delete
                                                            </button>
                                                        </span>
                                                    )}
                                                </div>
                                            </td>

                                            <td>
                                                {item.status ? (
                                                    <span className="badge bg-success">
                                                        Active
                                                    </span>
                                                ) : (
                                                    <span className="badge bg-danger">
                                                        Inactive
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

                                        <div
                                            className="modal fade"
                                            id={`updateCategory${item.id}`}
                                            tabIndex="-1"
                                            data-bs-backdrop="static"
                                        >
                                            <div
                                                className="modal-dialog modal-dialog-centered"
                                                style={{ maxWidth: "900px" }}
                                            >
                                                <div className="modal-content">
                                                    <div className="modal-header">
                                                        <h5 className="modal-title">
                                                            Update Category
                                                        </h5>
                                                        <button
                                                            type="button"
                                                            className="btn-close"
                                                            data-bs-dismiss="modal"
                                                        ></button>
                                                    </div>

                                                    <form
                                                        onSubmit={(e) =>
                                                            submitFormDataFU(
                                                                e,
                                                                item.id
                                                            )
                                                        }
                                                    >
                                                        <div className="modal-body">
                                                            <div className="row px-4">
                                                                <div className="col-md-12">
                                                                    <div className="form-group">
                                                                        <label>
                                                                            Category
                                                                            Name{" "}
                                                                            <span className="text-danger">
                                                                                *
                                                                            </span>
                                                                        </label>
                                                                        <input
                                                                            type="text"
                                                                            className="form-control"
                                                                            placeholder="Category Name"
                                                                            value={
                                                                                form
                                                                                    .data
                                                                                    .category_name
                                                                            }
                                                                            onChange={(
                                                                                e
                                                                            ) =>
                                                                                form.setData(
                                                                                    "category_name",
                                                                                    e
                                                                                        .target
                                                                                        .value
                                                                                )
                                                                            }
                                                                            required
                                                                        />
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div className="modal-footer">
                                                            <button
                                                                type="submit"
                                                                className="btn btn-success"
                                                                disabled={
                                                                    form.processing
                                                                }
                                                            >
                                                                Save
                                                            </button>
                                                            <button
                                                                type="button"
                                                                className="btn btn-danger"
                                                                data-bs-dismiss="modal"
                                                                id={`updateCategoryClose${item.id}`}
                                                            >
                                                                Cancel
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </React.Fragment>
                                ))}
                            </tbody>
                        </table>
                    </div>

                    <div className="d-flex justify-content-end pb-2">
                        {packageCategoryData.links.map((link, i) => (
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
            </div>

            <div
                className="modal fade"
                id="addCategory"
                tabIndex="-1"
                data-bs-backdrop="static"
            >
                <div className="modal-dialog modal-dialog-centered max-width-900px">
                    <div className="modal-content">
                        <div className="modal-header">
                            <h5 className="modal-title">New Category</h5>
                            <button
                                type="button"
                                className="btn-close"
                                data-bs-dismiss="modal"
                                id="addCategoryClose"
                            ></button>
                        </div>

                        <form onSubmit={submitFormData}>
                            <div className="modal-body">
                                <div className="row px-4">
                                    <div className="col-md-12">
                                        <div className="form-group">
                                            <label htmlFor="account_name">
                                                Category Name{" "}
                                                <span className="text-danger">
                                                    *
                                                </span>
                                            </label>
                                            <input
                                                type="text"
                                                className="form-control"
                                                placeholder="Category Name"
                                                value={form.data.category_name}
                                                onChange={(e) =>
                                                    form.setData(
                                                        "category_name",
                                                        e.target.value
                                                    )
                                                }
                                                required
                                            />
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div className="modal-footer">
                                <button
                                    type="submit"
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
