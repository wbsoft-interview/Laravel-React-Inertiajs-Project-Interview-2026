import { useState } from 'react';
import { BsThreeDotsVertical } from "react-icons/bs";
import { IoMdClose } from 'react-icons/io';
import Select from "react-select";

const Table = ({ tableData }) => {
    const [openUpdate, setOpenUpdate] = useState(false);
    const [openAdd, setOpenAdd] = useState(false);
    const [popOpen, setPopOpen] = useState(null);
    const [gProject, setGProject] = useState(null);
    const [currentPage, setCurrentPage] = useState(1);
    const [totalPages, setTotalPages] = useState(10);

    const selectStyles = {
        control: (base, state) => ({
            ...base,
            height: "40px",
            borderColor: state.isFocused ? "#1DAA61" : base.borderColor,
            boxShadow: state.isFocused ? "0 0 0 1px #1DAA61" : "none",
            "&:hover": {
                borderColor: state.isFocused ? "#1DAA61" : base.borderColor,
            },
        }),
        menuList: (base) => ({
            ...base,
            maxHeight: "200px",
            overflowY: "auto",
        }),
    };

    const project = [
        {
            id: 1,
            project_name: "Corporate Website Redesign"
        },
        {
            id: 2,
            project_name: "Mobile Banking Application"
        },
        {
            id: 3,
            project_name: "E-Commerce Platform Development"
        },
        {
            id: 4,
            project_name: "SEO & Digital Marketing Revamp"
        },
        {
            id: 5,
            project_name: "Cybersecurity Audit Portal"
        }
    ];


    const projects = project?.map((c) => ({
        value: c?.id,
        label: c?.project_name,
    })) || [];

    const handlePageChange = (page) => {
        if (page >= 1 && page <= totalPages) {
            setCurrentPage(page);
        }
    };

    return (
        <div className={` relative `}>
            <div className={`p-5`}>
                <div className="flex flex-wrap md:flex-nowrap md:items-center md:justify-between  gap-4 bg-secondary w-full mb-5 shadow-md">
                    <button className="bg-primary w-full lg:w-fit text-primary-content px-4 h-10 font-bold text-base ">
                        Table
                    </button>
                    <button onClick={() => setOpenAdd(true)} className=" w-full lg:w-fit items-center gap-1 font-bold text-primary-content bg-primary px-3 h-10 text-base cursor-pointer">
                        Create Table
                    </button>
                </div>

                <div className="w-full relative">
                    <table className="bg-base-200 border-separate border-spacing-0 rounded-md w-full min-w-max">
                        <thead className="h-10">
                            <tr className="uppercase text-center bg-accent text-primary-content font-medium text-base">
                                <th className="border border-accent px-4 py-2 whitespace-nowrap">Name</th>
                                <th className="border border-accent px-4 py-2 whitespace-nowrap">Phone</th>
                                <th className="border border-accent px-4 py-2 whitespace-nowrap">Email</th>
                                <th className="border border-accent px-4 py-2 whitespace-nowrap">Actions</th>
                            </tr>
                        </thead>

                        <tbody className="text-center text-primary-content text-sm">
                            {tableData?.map(row => {
                                const statusAction = row?.status === "Active" ? "Inactive" : "Active";

                                return (
                                    <tr key={row?.id}>
                                        <td className="border border-accent py-2 px-4 whitespace-nowrap">{row?.name}</td>
                                        <td className="border border-accent py-2 px-4 whitespace-nowrap">{row?.phone}</td>
                                        <td className="border border-accent py-2 px-4 whitespace-nowrap">{row?.email}</td>

                                        <td className="border border-accent relative whitespace-nowrap">
                                            <button
                                                onClick={() => setPopOpen(popOpen === row?.id ? null : row?.id)}
                                                className="border-none py-0 px-2"
                                            >
                                                <BsThreeDotsVertical className="text-xl cursor-pointer font-bold" />
                                            </button>

                                            {popOpen === row?.id && (
                                                <div className="absolute bg-base-200 dark:bg-color_dark_blue_gray shadow-lg w-52 text-start font-medium z-50 right-3.5 top-8">
                                                    <ul className="text-left p-2">
                                                        {[statusAction.toLowerCase(), "edit", "delete"].map(action => (
                                                            <li
                                                                key={action}
                                                                className="px-4 py-2 cursor-pointer hover:bg-color_gray_200 "
                                                                onClick={() => handleActionClick(action, row)}
                                                            >
                                                                {action.charAt(0).toUpperCase() + action.slice(1)}
                                                            </li>
                                                        ))}
                                                    </ul>
                                                </div>
                                            )}
                                        </td>
                                    </tr>
                                );
                            })}
                        </tbody>
                    </table>
                </div>

                {/* PAGINATION */}
                <div className="py-3 lg:flex items-center justify-between space-y-6 lg:space-y-0">
                    <div className="text-sm lg:text-base lg:text-left text-center w-full">
                        {t.showing}
                        <span>
                            {Math.min(startIndex + currentRows.length === 0 || rows.length === 0 ? 0 : startIndex + 1, rows.length)}
                        </span>{" "}
                        {t.to} <span>{Math.min(startIndex + currentRows.length, rows.length)}</span> of{" "}
                        <span>{rows.length}</span> {t.entries}
                    </div>

                    <div className="w-full flex lg:justify-end justify-center items-center gap-2 flex-wrap">
                        <button
                            onClick={() => handlePageChange(currentPageSafe - 1)}
                            disabled={currentPageSafe === 1}
                            className="px-3 py-1 border border-accent bg-base-200 cursor-pointer hover:bg-primary hover:text-neutral-content rounded disabled:opacity-50"
                        >
                            {t.prev}
                        </button>

                        <button
                            onClick={() => handlePageChange(1)}
                            className={`px-3 py-1 border bg-base-200 border-accent hover:bg-secondary ${currentPageSafe === 1 ? "bg-primary text-neutral-content" : ""
                                }`}
                        >
                            1
                        </button>

                        {currentPageSafe > 3 && <span className="px-2">...</span>}

                        {Array.from({ length: totalPages })
                            .map((_, i) => i + 1)
                            .filter((page) => page !== 1 && page !== totalPages && Math.abs(page - currentPageSafe) <= 1)
                            .map((page) => (
                                <button
                                    key={page}
                                    onClick={() => handlePageChange(page)}
                                    className={`px-3 py-1 border bg-base-200 border-accent hover:bg-secondary ${page === currentPageSafe ? "bg-primary text-neutral-content" : ""
                                        }`}
                                >
                                    {page}
                                </button>
                            ))}

                        {currentPageSafe < totalPages - 2 && <span className="px-2">...</span>}

                        {totalPages > 1 && (
                            <button
                                onClick={() => handlePageChange(totalPages)}
                                className={`px-3 py-1 border bg-base-200 border-accent hover:bg-secondary ${currentPageSafe === totalPages ? "bg-primary text-neutral-content" : ""
                                    }`}
                            >
                                {totalPages}
                            </button>
                        )}

                        <button
                            onClick={() => handlePageChange(currentPageSafe + 1)}
                            disabled={currentPageSafe === totalPages}
                            className="px-3 py-1 border border-accent bg-base-200 hover:bg-primary hover:text-neutral-content cursor-pointer disabled:opacity-50"
                        >
                            {t.next}
                        </button>
                    </div>
                </div>


                {openUpdate && (
                    <div className="fixed inset-0 flex items-center justify-center bg-black/30 z-50 px-3">
                        <div className="bg-base-200 rounded-lg shadow-lg p-6 w-full max-w-2xl space-y-4">
                            <div className="flex justify-between items-center">
                                <h2 className="text-base sm:text-lg font-semibold">Add Table</h2>

                                <button
                                    type="button"
                                    onClick={() => setOpenUpdate(false)}
                                    className="text-sm text-primary-content cursor-pointer hover:text-error"
                                >
                                    <IoMdClose size={24} />
                                </button>
                            </div>
                            <div>
                                <div className="relative space-y-2">

                                    <input
                                        type="text"
                                        id="name"
                                        name="name"
                                        placeholder='Name'
                                        className="w-full max-h-10 border border-accent px-3 py-2 rounded outline-none focus:ring-2 focus:ring-primary"
                                    />
                                    <input
                                        type="number"
                                        id="phone"
                                        name="phone"
                                        placeholder='Phone number'
                                        className="w-full max-h-10 border border-accent px-3 py-2 rounded outline-none focus:ring-2 focus:ring-primary"
                                    />
                                    <input
                                        type="email"
                                        id="email"
                                        name="email"
                                        placeholder='Email'
                                        className="w-full max-h-10 border border-accent px-3 py-2 rounded outline-none focus:ring-2 focus:ring-primary"
                                    />
                                    <div className='flex items-center justify-end'>
                                        <button type='submit' className="bg-primary text-primary-content py-2 px-4 cursor-pointer rounded-md hover:bg-primary transition">
                                            Add
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                )}
            </div>

            <div className={` ${openAdd ? "fixed top-0 right-0 max-w-xl bg-base-200 h-screen overflow-y-scroll p-5" : "hidden"}`}>
                <div className='flex items-center justify-between'>
                    <h1 className="text-base sm:text-lg font-semibold">Create Table</h1>
                    <button
                        type="button"
                        onClick={() => setOpenAdd(false)}
                        className="text-sm text-primary-content cursor-pointer hover:text-error"
                    >
                        <IoMdClose size={24} />
                    </button>
                </div>

                <form className='mt-4 space-y-4'>
                    <label className="pb-1 font-semibold">Name <span className="text-red-500">*</span></label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        placeholder='Name'
                        className="w-full max-h-10 border border-accent px-3 py-2 rounded outline-none focus:ring-2 focus:ring-primary"
                    />

                    <label className="pb-1 font-semibold">Phone Number <span className="text-red-500">*</span></label>
                    <input
                        type="number"
                        id="phone"
                        name="phone"
                        placeholder='Phone Number'
                        className="w-full max-h-10 border border-accent px-3 py-2 rounded outline-none focus:ring-2 focus:ring-primary"
                    />

                    <label className="pb-1 font-semibold">Email <span className="text-red-500">*</span></label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        placeholder='Email'
                        className="w-full max-h-10 border border-accent px-3 py-2 rounded outline-none focus:ring-2 focus:ring-primary"
                    />

                    <div>
                        <label className="pb-1 font-semibold">Project <span className="text-red-500">*</span></label>
                        <Select
                            options={projects}
                            value={projects.find((option) => option.value === gProject) || null}
                            onChange={(option) => setGProject(option.value)}
                            placeholder="Select A Project"
                            className="w-full"
                            styles={selectStyles}
                        />
                    </div>
                    <div className='flex items-center justify-end gap-5'>
                        <button type='submit' className="bg-secondary text-primary-content py-2 px-4 cursor-pointer rounded-md hover:bg-error-content hover:text-secondary-content transition">
                            Cancel
                        </button>
                        <button type='submit' className="bg-primary text-primary-content py-2 px-4 cursor-pointer rounded-md hover:bg-info transition">Add Table</button>
                    </div>
                </form>
            </div>
        </div>
    );
};

export default Table;