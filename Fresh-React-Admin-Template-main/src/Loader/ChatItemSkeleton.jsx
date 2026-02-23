import Skeleton from "react-loading-skeleton";
import 'react-loading-skeleton/dist/skeleton.css';

const ChatItemSkeleton = () => (
    <div>
        <div className="p-4 flex  items-center gap-4 w-full">
            {/* Avatar */}
            <div className="flex-shrink-0">
                <Skeleton circle height={64} width={64} />
            </div>

            {/* Content */}
            <div className="flex-grow w-full space-y-2">
                {/* Top Row */}
                <div className="flex flex-wrap justify-between items-center gap-2">
                    <Skeleton width={150} height={20} />
                    <Skeleton width={50} height={20} />
                </div>

                {/* Bottom Row */}
                <div className="flex flex-wrap justify-between items-center gap-2">
                    <Skeleton width={70} height={20} />
                </div>
            </div>
        </div>

        <div className="p-4 flex  items-center gap-4 w-full">
            {/* Avatar */}
            <div className="flex-shrink-0">
                <Skeleton circle height={64} width={64} />
            </div>

            {/* Content */}
            <div className="flex-grow w-full space-y-2">
                {/* Top Row */}
                <div className="flex flex-wrap justify-between items-center gap-2">
                    <Skeleton width={150} height={20} />
                    <Skeleton width={50} height={20} />
                </div>

                {/* Bottom Row */}
                <div className="flex flex-wrap justify-between items-center gap-2">
                    <Skeleton width={70} height={20} />
                </div>
            </div>
        </div>

        <div className="p-4 flex  items-center gap-4 w-full">
            {/* Avatar */}
            <div className="flex-shrink-0">
                <Skeleton circle height={64} width={64} />
            </div>

            {/* Content */}
            <div className="flex-grow w-full space-y-2">
                {/* Top Row */}
                <div className="flex flex-wrap justify-between items-center gap-2">
                    <Skeleton width={150} height={20} />
                    <Skeleton width={50} height={20} />
                </div>

                {/* Bottom Row */}
                <div className="flex flex-wrap justify-between items-center gap-2">
                    <Skeleton width={70} height={20} />
                </div>
            </div>
        </div>

        <div className="p-4 flex  items-center gap-4 w-full">
            {/* Avatar */}
            <div className="flex-shrink-0">
                <Skeleton circle height={64} width={64} />
            </div>

            {/* Content */}
            <div className="flex-grow w-full space-y-2">
                {/* Top Row */}
                <div className="flex flex-wrap justify-between items-center gap-2">
                    <Skeleton width={150} height={20} />
                    <Skeleton width={50} height={20} />
                </div>

                {/* Bottom Row */}
                <div className="flex flex-wrap justify-between items-center gap-2">
                    <Skeleton width={70} height={20} />
                </div>
            </div>
        </div>

        <div className="p-4 flex  items-center gap-4 w-full">
            {/* Avatar */}
            <div className="flex-shrink-0">
                <Skeleton circle height={64} width={64} />
            </div>

            {/* Content */}
            <div className="flex-grow w-full space-y-2">
                {/* Top Row */}
                <div className="flex flex-wrap justify-between items-center gap-2">
                    <Skeleton width={150} height={20} />
                    <Skeleton width={50} height={20} />
                </div>

                {/* Bottom Row */}
                <div className="flex flex-wrap justify-between items-center gap-2">
                    <Skeleton width={70} height={20} />
                </div>
            </div>
        </div>
    </div>
);

export default ChatItemSkeleton;
