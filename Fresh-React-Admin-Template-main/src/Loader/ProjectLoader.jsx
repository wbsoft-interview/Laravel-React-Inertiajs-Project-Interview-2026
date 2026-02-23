import Skeleton from "react-loading-skeleton";
import 'react-loading-skeleton/dist/skeleton.css';

const ProjectLoader = () => {
    const projectCount = 4;

    return (
        <>
            {[...Array(projectCount)].map((_, i) => (
                <div key={i} className="bg-base-100 w-full p-4 relative shadow-xl rounded-lg space-y-3">
                    {/* Top Row: Avatar + Title */}
                    <div className="flex justify-between items-start">
                        <div className="flex items-center gap-2">
                            <Skeleton circle height={32} width={32} />
                            <Skeleton height={20} width={150} borderRadius={4} />
                        </div>
                        <Skeleton height={20} width={20} borderRadius={50} />
                    </div>

                    {/* Date Row */}
                    <div className="flex items-center gap-2">
                        <Skeleton height={16} width={16} circle />
                        <Skeleton height={16} width={100} />
                    </div>

                    {/* Leads Row */}
                    <div className="flex items-center gap-2">
                        <Skeleton height={16} width={16} circle />
                        <Skeleton height={16} width={80} />
                    </div>
                </div>
            ))}
        </>
    );
};

export default ProjectLoader;
