import { useState } from "react";
import { FiFilter } from "react-icons/fi";
import { GoPlusCircle } from "react-icons/go";
import { IoIosArrowDown } from "react-icons/io";

const Card = () => {
    const students = [
        {
            name: "Arif Rahman",
            email: "arif.rahman@example.com",
            phone: "+8801711001100",
            dob: "12-Jan-2002",
            gender: "Male",
            department: "Science",
            proficiency: "IELTS",
            destination: "USA",
            image: "https://randomuser.me/api/portraits/men/11.jpg",
            address: "Dhaka",
            sscGpa: "5.00",
            hscGpa: "4.80"
        },
        {
            name: "Mahiya Tasnim",
            email: "mahiya.tasnim@example.com",
            phone: "+8801711002200",
            dob: "25-Mar-2003",
            gender: "Female",
            department: "Arts",
            proficiency: "TOEFL",
            destination: "Canada",
            image: "https://randomuser.me/api/portraits/women/21.jpg",
            address: "Chittagong",
            sscGpa: "4.90",
            hscGpa: "4.70"
        },
        {
            name: "Samiul Haque",
            email: "samiul.haque@example.com",
            phone: "+8801711003300",
            dob: "17-May-2001",
            gender: "Male",
            department: "Commerce",
            proficiency: "PTE",
            destination: "UK",
            image: "https://randomuser.me/api/portraits/men/31.jpg",
            address: "Sylhet",
            sscGpa: "4.50",
            hscGpa: "4.20"
        },
        {
            name: "Nusrat Jahan",
            email: "nusrat.jahan@example.com",
            phone: "+8801711004400",
            dob: "09-Oct-2002",
            gender: "Female",
            department: "Science",
            proficiency: "IELTS, TOEFL",
            destination: "Australia",
            image: "https://randomuser.me/api/portraits/women/32.jpg",
            address: "Rajshahi",
            sscGpa: "5.00",
            hscGpa: "5.00"
        },
        {
            name: "Rakibul Islam",
            email: "rakibul.islam@example.com",
            phone: "+8801711005500",
            dob: "30-Dec-2000",
            gender: "Male",
            department: "Science",
            proficiency: "IELTS",
            destination: "Germany",
            image: "https://randomuser.me/api/portraits/men/41.jpg",
            address: "Khulna",
            sscGpa: "4.70",
            hscGpa: "4.60"
        },
        {
            name: "Tanjila Akter",
            email: "tanjila.akter@example.com",
            phone: "+8801711006600",
            dob: "14-Feb-2004",
            gender: "Female",
            department: "Arts",
            proficiency: "PTE",
            destination: "France",
            image: "https://randomuser.me/api/portraits/women/41.jpg",
            address: "Barishal",
            sscGpa: "4.90",
            hscGpa: "4.75"
        },
        {
            name: "Hasib Khan",
            email: "hasib.khan@example.com",
            phone: "+8801711007700",
            dob: "03-Aug-2001",
            gender: "Male",
            department: "Science",
            proficiency: "IELTS",
            destination: "USA",
            image: "https://randomuser.me/api/portraits/men/51.jpg",
            address: "Dhaka",
            sscGpa: "5.00",
            hscGpa: "4.95"
        },
        {
            name: "Rumana Akter",
            email: "rumana.akter@example.com",
            phone: "+8801711008800",
            dob: "18-Apr-2003",
            gender: "Female",
            department: "Commerce",
            proficiency: "TOEFL",
            destination: "UK",
            image: "https://randomuser.me/api/portraits/women/45.jpg",
            address: "Chittagong",
            sscGpa: "4.80",
            hscGpa: "4.65"
        },
        {
            name: "Fahim Hasan",
            email: "fahim.hasan@example.com",
            phone: "+8801711009900",
            dob: "27-Sep-2002",
            gender: "Male",
            department: "Science",
            proficiency: "IELTS",
            destination: "Canada",
            image: "https://randomuser.me/api/portraits/men/61.jpg",
            address: "Sylhet",
            sscGpa: "5.00",
            hscGpa: "4.85"
        },
        {
            name: "Sanaya Hossain",
            email: "sanaya.hossain@example.com",
            phone: "+8801711011100",
            dob: "22-Jul-2004",
            gender: "Female",
            department: "Arts",
            proficiency: "IELTS, PTE",
            destination: "Australia",
            image: "https://randomuser.me/api/portraits/women/52.jpg",
            address: "Rajshahi",
            sscGpa: "4.95",
            hscGpa: "4.90"
        },
        {
            name: "Mahmudul Karim",
            email: "mahmudul.karim@example.com",
            phone: "+8801711012200",
            dob: "11-Jan-2001",
            gender: "Male",
            department: "Commerce",
            proficiency: "TOEFL",
            destination: "Germany",
            image: "https://randomuser.me/api/portraits/men/72.jpg",
            address: "Khulna",
            sscGpa: "4.60",
            hscGpa: "4.55"
        },
        {
            name: "Lamia Noor",
            email: "lamia.noor@example.com",
            phone: "+8801711013300",
            dob: "13-Nov-2002",
            gender: "Female",
            department: "Science",
            proficiency: "IELTS",
            destination: "USA",
            image: "https://randomuser.me/api/portraits/women/65.jpg",
            address: "Dhaka",
            sscGpa: "5.00",
            hscGpa: "4.98"
        },
        {
            name: "Omar Faruk",
            email: "omar.faruk@example.com",
            phone: "+8801711014400",
            dob: "29-Apr-2003",
            gender: "Male",
            department: "Science",
            proficiency: "PTE",
            destination: "Canada",
            image: "https://randomuser.me/api/portraits/men/82.jpg",
            address: "Chittagong",
            sscGpa: "4.85",
            hscGpa: "4.70"
        },
        {
            name: "Farhana Islam",
            email: "farhana.islam@example.com",
            phone: "+8801711015500",
            dob: "06-Jun-2001",
            gender: "Female",
            department: "Arts",
            proficiency: "IELTS",
            destination: "UK",
            image: "https://randomuser.me/api/portraits/women/72.jpg",
            address: "Rajshahi",
            sscGpa: "4.70",
            hscGpa: "4.40"
        },
        {
            name: "Imran Chowdhury",
            email: "imran.chowdhury@example.com",
            phone: "+8801711016600",
            dob: "19-Feb-2002",
            gender: "Male",
            department: "Commerce",
            proficiency: "TOEFL",
            destination: "Australia",
            image: "https://randomuser.me/api/portraits/men/19.jpg",
            address: "Sylhet",
            sscGpa: "4.95",
            hscGpa: "4.65"
        },
        {
            name: "Shaila Rahman",
            email: "shaila.rahman@example.com",
            phone: "+8801711017700",
            dob: "08-Oct-2003",
            gender: "Female",
            department: "Science",
            proficiency: "IELTS",
            destination: "USA",
            image: "https://randomuser.me/api/portraits/women/75.jpg",
            address: "Khulna",
            sscGpa: "5.00",
            hscGpa: "4.88"
        },
        {
            name: "Rezaul Karim",
            email: "rezaul.karim@example.com",
            phone: "+8801711018800",
            dob: "04-Dec-2000",
            gender: "Male",
            department: "Science",
            proficiency: "PTE",
            destination: "France",
            image: "https://randomuser.me/api/portraits/men/88.jpg",
            address: "Barishal",
            sscGpa: "4.55",
            hscGpa: "4.30"
        },
        {
            name: "Anika Sultana",
            email: "anika.sultana@example.com",
            phone: "+8801711019900",
            dob: "15-May-2002",
            gender: "Female",
            department: "Arts",
            proficiency: "IELTS, TOEFL",
            destination: "Germany",
            image: "https://randomuser.me/api/portraits/women/80.jpg",
            address: "Dhaka",
            sscGpa: "4.90",
            hscGpa: "4.85"
        },
        {
            name: "Tanvir Ahmed",
            email: "tanvir.ahmed@example.com",
            phone: "+8801711020000",
            dob: "23-Aug-2001",
            gender: "Male",
            department: "Science",
            proficiency: "IELTS",
            destination: "UK",
            image: "https://randomuser.me/api/portraits/men/90.jpg",
            address: "Chittagong",
            sscGpa: "5.00",
            hscGpa: "4.90"
        }
    ];

    const [openAdd, setOpenAdd] = useState(false);
    const [currentPage, setCurrentPage] = useState(1);
    const [totalPages, setTotalPages] = useState(10);
    const [openPageSize, setOpenPageSize] = useState(false);
    const [pageSizeValue, setPageSizeValue] = useState("10");
    const pageSize = parseInt(pageSizeValue, 10) || 10;
    const pageSizeOptions = ["10", "20", "50", "100"];
    const [searchTerm, setSearchTerm] = useState("");

    const handlePageChange = (page) => {
        if (page >= 1 && page <= totalPages) {
            setCurrentPage(page);
        }
    };

    const handleSearchSubmit = (e) => {
        e.preventDefault();

        setSearchTerm(e.target.search.value);
    };
    return (
        <div className="p-5 space-y-5 ">
            <div className="py-3 text-primary-content flex items-center justify-between gap-5">
                <h1 className="font-semibold text-3xl">Card</h1>

                <div className="flex items-center gap-2">
                    {/* SEARCH */}
                    <form onSubmit={handleSearchSubmit} className="flex items-center">
                        <input
                            type="text"
                            id="search"
                            name="search"
                            placeholder="Search Here"
                            className="border-2 border-primary h-10 px-2 focus:outline-none rounded-s text-primary-content"
                        />
                        <button
                            type="submit"
                            className="flex items-center gap-1 font-bold text-primary-content bg-primary hover:bg-primary-hover px-3 py-2 text-base rounded-e cursor-pointer "
                        >
                            Search
                        </button>
                    </form>

                    {/* PAGE SIZE DROPDOWN */}
                    <div className="relative w-16">
                        <button
                            onClick={() => setOpenPageSize(!openPageSize)}
                            className="w-full py-1.5 border-2 border-primary bg-base-200 rounded  cursor-pointer flex items-center justify-center gap-2"
                        >
                            {pageSizeValue}{" "}
                            <IoIosArrowDown
                                className={`transition-transform duration-300 ${openPageSize && "-rotate-180"
                                    }`}
                            />
                        </button>

                        <div
                            className={`absolute z-20 w-full mt-1 bg-base-200 border-2 border-primary rounded shadow overflow-hidden transition-all duration-200 origin-top ${openPageSize ? "opacity-100 scale-y-100" : "opacity-0 scale-y-0"
                                }`}
                        >
                            {pageSizeOptions?.map((opt) => (
                                <div
                                    key={opt}
                                    onClick={() => handleSelectPageSize(opt)}
                                    className=" text-center py-2 hover:bg-gray-100 cursor-pointer"
                                >
                                    {opt}
                                </div>
                            ))}
                        </div>
                    </div>

                    {/* FILTER BUTTON */}
                    <button
                        onClick={() => setOpenFilter(true)}
                        className="flex items-center gap-1 font-bold text-primary bg-primary-light hover:bg-primary-light-hover px-3 py-2 text-base rounded cursor-pointer "
                    >
                        <FiFilter />
                        Filter
                    </button>

                    {/* ADD BUTTON */}
                    <button
                        onClick={() => setOpenAdd(true)}
                        className="flex items-center gap-1 font-bold text-primary-content bg-primary hover:bg-primary-hover px-3 py-2 text-base rounded cursor-pointer "
                    >
                        <GoPlusCircle />
                        Add Table
                    </button>
                </div>
            </div>
            

            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                {students?.map((data, i) => (
                    <div key={i} className="bg-base-200 shadow-box  hover:scale-102 transition-transform duration-300 flex flex-col justify-between rounded-lg overflow-hidden">

                        {/* Top Section: Image + Info */}
                        <div className="p-5 flex gap-4">
                            <div className="w-40 overflow-hidden rounded-lg">
                                <img
                                    src={data?.image}
                                    alt={data?.name}
                                    className="h-52 w-full object-cover"
                                />
                            </div>

                            <div className="flex-1 pl-4">
                                <ul className="text-primary-content text-left space-y-1">
                                    <li><span className="font-bold">Name:</span> <span className="font-normal">{data?.name}</span></li>
                                    <li><span className="font-bold">Email:</span> <span className="font-normal">{data?.email}</span></li>
                                    <li><span className="font-bold">Phone:</span> <span className="font-normal">{data?.phone}</span></li>
                                    <li><span className="font-bold">DOB:</span> <span className="font-normal">{data?.dob}</span></li>
                                    <li><span className="font-bold">Gender:</span> <span className="font-normal">{data?.gender}</span></li>
                                    <li><span className="font-bold">Department:</span> <span className="font-normal">{data?.department}</span></li>
                                    <li><span className="font-bold">Proficiency:</span> <span className="font-normal">{data?.proficiency}</span></li>
                                    <li><span className="font-bold">Destination:</span> <span className="font-normal">{data?.destination}</span></li>
                                </ul>
                            </div>
                        </div>

                        {/* Progress Bars */}
                        <div className="flex gap-2 mx-5 mt-5">
                            <div className="flex-1 h-1 bg-primary/50"></div>
                            <div className="flex-1 h-1 bg-primary/65"></div>
                            <div className="flex-1 h-1 bg-primary/80"></div>
                            <div className="flex-1 h-1 bg-primary"></div>
                        </div>

                        {/* Address + GPA */}
                        <div className="flex p-5 gap-5 text-center">
                            <div className="w-1/2">
                                <p className="font-bold text-primary-content">
                                    Address: <span className="font-normal">{data?.address}</span>
                                </p>
                            </div>

                            <div className="w-1/2 border-l border-border-color space-y-1">
                                <p className="font-semibold text-primary-content">GPA of SSC: <span>{data?.sscGpa}</span></p>
                                <p className="font-semibold text-primary-content">GPA of HSC: <span>{data?.hscGpa}</span></p>
                            </div>
                        </div>

                        {/* Buttons */}
                        <div className="flex gap-2 p-5">
                            <button className="w-full bg-primary hover:bg-primary-hover text-white cursor-pointer font-semibold py-2 rounded transition-colors">
                                View
                            </button>
                            <button className="w-full bg-primary-light text-primary font-semibold cursor-pointer py-2 rounded transition-colors">
                                Login
                            </button>
                        </div>
                    </div>
                ))}
            </div>

            {/* Pagination */}
            <div className="flex justify-center items-center gap-2 mt-6 flex-wrap">
                <button
                    onClick={() => handlePageChange(currentPage - 1)}
                    disabled={currentPage === 1}
                    className="px-3 py-1 border border-accent bg-base-200 cursor-pointer hover:bg-primary hover:text-neutral-content rounded disabled:opacity-50"
                >
                    Prev
                </button>
                {[...Array(totalPages)].map((_, index) => {
                    const page = index + 1;
                    return (
                        <button
                            key={page}
                            onClick={() => handlePageChange(page)}
                            className={`px-3 py-1 border bg-base-200 hover:bg-secondary cursor-pointer border-accent ${page === currentPage ? "bg-primary text-neutral-content" : ""
                                }`}
                        >
                            {page}
                        </button>
                    );
                })}
                <button
                    onClick={() => handlePageChange(currentPage + 1)}
                    disabled={currentPage === totalPages}
                    className="px-3 py-1 border border-accent bg-base-200 hover:bg-primary hover:text-neutral-content cursor-pointer disabled:opacity-50"
                >
                    Next
                </button>
            </div>

        </div>
    );
};

export default Card;