import { IoCloseSharp } from "react-icons/io5";
const MessageInfoLoader = () => {
    return (
        <div className="w-96 h-screen bg-base-200 border-l border-accent shadow-md flex flex-col overflow-y-auto ml-2 pl-2">
            <div className="flex items-center gap-5 mb-4">
                <div>
                    <h1><IoCloseSharp size={24} /></h1>

                </div>
                <div>
                    Message Info
                </div>

            </div>
            <div className="h-2/12 bg-gray-300 rounded p-5 mr-4 animate-pulse"></div>
            <div className="">
                <div className="h-6 w-12 bg-gray-300 m-2 rounded"></div>
                <div className="border-b-2 border-accent">
                    <div className="flex items-center pl-2 pt-5">
                        <div className="w-12 h-12 bg-gray-300 rounded-full animate-pulse"></div>
                        <div className="pl-4 space-y-2">
                            <p className="h-6 w-50 bg-gray-300 rounded animate-pulse"></p>
                            <p className="h-4 w-30 bg-gray-300 rounded animate-pulse"></p>
                        </div>
                    </div>

                    <div className="flex items-center pl-2 pt-5">
                        <div className="w-12 h-12 bg-gray-300 rounded-full animate-pulse"></div>
                        <div className="pl-4 space-y-2">
                            <p className="h-6 w-50 bg-gray-300 rounded animate-pulse"></p>
                            <p className="h-4 w-30 bg-gray-300 rounded animate-pulse"></p>
                        </div>
                    </div>

                    <div className="h-6 w-20 bg-gray-300 rounded m-3"></div>
                </div>
                <div>
                    <div className="h-6 w-20 bg-gray-300 m-3 rounded"></div>
                    <div className="flex items-center pl-2 pt-5">
                        <div className="w-12 h-12 bg-gray-300 rounded-full animate-pulse"></div>
                        <div className="pl-4 space-y-2">
                            <p className="h-6 w-50 bg-gray-300 rounded animate-pulse"></p>
                            <p className="h-4 w-30 bg-gray-300 rounded animate-pulse"></p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    );
};

export default MessageInfoLoader;
