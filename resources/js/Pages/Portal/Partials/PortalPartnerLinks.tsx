import Box from "@mui/material/Box";
import Button from "@mui/material/Button";
import AddIcon from '@mui/icons-material/Add';
import {useForm} from '@inertiajs/react';
import {
    closestCenter,
    DndContext,
    DragEndEvent,
    KeyboardSensor,
    PointerSensor,
    useSensor,
    useSensors
} from '@dnd-kit/core';
import {arrayMove, SortableContext, sortableKeyboardCoordinates, verticalListSortingStrategy} from '@dnd-kit/sortable';
import SortableFlow from "@/Pages/Portal/Partials/SortableFlow";
import {CountriesDict} from "@/types/country";


interface PortalPartnerLinkFormData {
    [key: string]: any;
    portal_partner_links: PortalPartnerLink[];
}

export default function PortalPartnerLinks({portalId, portalPartnerLinks, partnerLinks, countries, landings}: {
    portalId: number,
    portalPartnerLinks: PortalPartnerLink[],
    partnerLinks: PartnerLink[],
    countries: CountriesDict,
    landings: GroupedLandings,
}) {
    const sensors = useSensors(
        useSensor(PointerSensor),
        useSensor(KeyboardSensor, {
            coordinateGetter: sortableKeyboardCoordinates,
        })
    );


    const { data, setData, post, errors, hasErrors, processing, wasSuccessful, reset } = useForm<PortalPartnerLinkFormData>({
        portal_partner_links: portalPartnerLinks?.map((link, index) => ({
            ...link,
            is_fallback: index === portalPartnerLinks.length - 1
        }))
    });

    const updateData = (links: any[]) => {
        setData('portal_partner_links', links.map((link, index) => ({
            ...link,
            priority: index
        })));
    };


    const addFlow = () => {
        const newLinks = [];

        if (data.portal_partner_links.length > 0) {
            const existingLinks = data.portal_partner_links.slice(0, -1).map(link => ({
                ...link,
                is_fallback: false
            }));

            newLinks.push(...existingLinks);
        }

        newLinks.push({
            id: 0,
            portal_id: portalId,
            partner_link_id: partnerLinks[0]?.id || 0,
            conditions: {},
            priority: newLinks.length,
            is_fallback: false
        });

        if (data.portal_partner_links.length === 0) {
            newLinks[0].is_fallback = true;
        } else {
            newLinks.push(data.portal_partner_links[data.portal_partner_links.length - 1]);
        }

        updateData(newLinks);
    };

    const removeFlow = (index: number) => {
        if (index === data.portal_partner_links.length - 1) return;
        const newLinks = [...data.portal_partner_links];
        newLinks.splice(index, 1);
        if (newLinks.length > 0) {
            newLinks[newLinks.length - 1].is_fallback = true;
        }
        updateData(newLinks);
    };

    const handleDragEnd = (event: DragEndEvent) => {
        const { active, over } = event;

        if (!over || active.id === over.id) return;

        const oldIndex = parseInt(active.id.toString());
        const newIndex = parseInt(over.id.toString());

        if (newIndex === data.portal_partner_links.length - 1 || oldIndex === data.portal_partner_links.length - 1) return;
        let newLinks = [...data.portal_partner_links];
        const fallback = newLinks.pop();

        newLinks = arrayMove(newLinks, oldIndex, newIndex);
        if (fallback) {
            newLinks.push(fallback);
        }
        newLinks = newLinks.map((item, index) => ({
            ...item,
            is_fallback: index === newLinks.length - 1
        }));

        updateData(newLinks);
    };

    const toggleCondition = (flowIndex: number, type: 'country' | 'device' | 'landings', enabled: boolean) => {
        if (flowIndex === data.portal_partner_links.length - 1) return;

        const newLinks = [...data.portal_partner_links];
        if (enabled) {
            newLinks[flowIndex].conditions = {
                ...newLinks[flowIndex].conditions,
                [type]: type === 'country'
                    ? { operator: 'in', values: [] }
                    : type === 'landings'
                        ? { values: [] } // Массив landings
                        : { value: 'desktop' }
            };
        } else {
            const { [type]: _, ...rest } = newLinks[flowIndex].conditions;
            newLinks[flowIndex].conditions = rest;
        }
        updateData(newLinks);
    };


    const handleSubmit = () => {
        const formData = {...data};
        if (formData.portal_partner_links.length > 0) {
            const lastIndex = formData.portal_partner_links.length - 1;
            formData.portal_partner_links = formData.portal_partner_links.map((link, index) => ({
                ...link,
                conditions: index === lastIndex ? {} : link.conditions,
                is_fallback: index === lastIndex
            }));
        }
        // @ts-ignore
        post(route('portal-partner-links.store'), formData);
    };

    return (
        <>
            <DndContext
                sensors={sensors}
                collisionDetection={closestCenter}
                onDragEnd={handleDragEnd}
            >
                <SortableContext
                    items={data.portal_partner_links.map((_, index) => index.toString())}
                    strategy={verticalListSortingStrategy}
                >
                    {data.portal_partner_links.map((flow, flowIndex) => (
                        <SortableFlow
                            key={flowIndex}
                            flow={flow}
                            flowIndex={flowIndex}
                            isFallback={flowIndex === data.portal_partner_links.length - 1}
                            onRemove={() => removeFlow(flowIndex)}
                            onPartnerLinkChange={(e) => {
                                const newLinks = [...data.portal_partner_links];
                                newLinks[flowIndex].partner_link_id = Number(e.target.value);
                                setData('portal_partner_links', newLinks);
                            }}
                            onConditionOperatorChange={(value) => {
                                const newLinks = [...data.portal_partner_links];
                                if (newLinks[flowIndex].conditions.country) {
                                    newLinks[flowIndex].conditions.country.operator = value;
                                }
                                setData('portal_partner_links', newLinks);
                            }}
                            onConditionValuesChange={(type: 'landings' | 'country', values: string[]) => {
                                const newLinks = [...data.portal_partner_links];
                                if (newLinks[flowIndex].conditions[type]) {
                                    newLinks[flowIndex].conditions[type].values = values;
                                }
                                setData('portal_partner_links', newLinks);
                            }}
                            onDeviceValueChange={(value) => {
                                const newLinks = [...data.portal_partner_links];
                                if (newLinks[flowIndex].conditions.device) {
                                    newLinks[flowIndex].conditions.device.value = value;
                                }
                                setData('portal_partner_links', newLinks);
                            }}
                            partnerLinks={partnerLinks}
                            toggleCondition={toggleCondition}
                            countries={countries}
                            landings={landings}
                        />
                    ))}
                </SortableContext>
            </DndContext>

            <Box sx={{ mt: 2, display: 'flex', gap: 2 }}>
                <Button
                    variant="contained"
                    startIcon={<AddIcon />}
                    onClick={addFlow}
                >
                    New flow
                </Button>
                <Button
                    variant="contained"
                    loading={processing}
                    onClick={handleSubmit}
                >
                    Save
                </Button>
            </Box>
        </>
    );
}
