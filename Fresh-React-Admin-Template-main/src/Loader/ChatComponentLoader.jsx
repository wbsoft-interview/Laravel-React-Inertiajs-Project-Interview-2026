const ChatComponentLoader = ({ messageCount }) => {
    return (
        <div className="h-screen flex flex-col bg-base-200 animate-pulse">
            {/* Header */}
            <div className="flex items-center p-3 border-b border-accent">
                <div className="flex items-center space-x-3">
                    <div className="w-10 h-10 bg-gray-200 rounded-full "></div>
                    <div className="flex flex-col gap-2">
                        <div className="h-4 w-24 bg-gray-200 rounded"></div>
                        <div className="h-3 w-16 bg-gray-200 rounded"></div>
                    </div>
                </div>
                <div className="flex items-center gap-3 ml-auto">
                    <div className="h-6 w-6 bg-gray-200 rounded-full"></div>
                    <div className="h-6 w-6 bg-gray-200 rounded-full"></div>
                    <div className="h-6 w-2 bg-gray-200 rounded"></div>
                </div>
            </div>

            {/* Chat Body */}
            <div className="flex-1 p-4 space-y-4 overflow-y-auto">
                {[...Array(messageCount)].map((_, i) => (
                    <div
                        key={i}
                        className={`flex ${i % 2 === 0 ? "justify-start" : "justify-end"}`}>
                        <div className="max-w-xs px-4 py-3 rounded-lg bg-gray-200">
                            <div className="h-3 w-32 bg-gray-300 rounded"></div>
                        </div>
                    </div>
                ))}
            </div>

            {/* Input */}
            <div className="p-3 border-t border-accent flex items-center gap-2 bg-base-200">
                <div className="h-8 w-8 bg-gray-300 rounded-full"></div>
                <div className="h-8 w-8 bg-gray-300 rounded-full"></div>
                <div className="flex-1 h-8 bg-gray-200 rounded-full"></div>
                <div className="h-8 w-8 bg-gray-300 rounded-full"></div>
            </div>
        </div>

    );
};

export default ChatComponentLoader;
