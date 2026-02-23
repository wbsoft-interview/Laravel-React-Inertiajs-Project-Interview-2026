const TableLoader = ({ rowCount }) => {
    return (
        <div className="">
            <table className="table w-full">
                <thead className="h-[40px]">
                    <tr className="uppercase text-center h-[40px] bg-base-200 text-black dark:bg-black/60 dark:text-primary-content font-bold">
                        <th className=""></th>
                        <th className=" ">Name</th>
                        <th className=" ">Email</th>
                        <th className=" ">Phone</th>
                        <th className=" ">Role</th>
                        <th className=" ">Status</th>
                        <th className=" ">Date/Time</th>
                        <th className=" ">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    {[...Array(rowCount)].map((_, i) => (
                        <tr key={i} className="animate-pulse">
                            <td><div className="w-6 h-6 bg-gray-300 rounded"></div></td>
                            <td><div className="w-20 h-6 bg-gray-300 rounded"></div></td>
                            <td><div className="w-40 h-6 bg-gray-300 rounded"></div></td>
                            <td><div className="w-24 h-6 bg-gray-300 rounded"></div></td>
                            <td><div className="w-20 h-6 bg-gray-300 rounded"></div></td>
                            <td><div className="w-20 h-6 bg-gray-300 rounded"></div></td>
                            <td><div className="w-28 h-6 bg-gray-300 rounded"></div></td>
                            <td><div className="w-6 h-6 bg-gray-300 rounded"></div></td>
                        </tr>
                    ))}
                </tbody>
            </table>
        </div>
    );
};

export default TableLoader;
