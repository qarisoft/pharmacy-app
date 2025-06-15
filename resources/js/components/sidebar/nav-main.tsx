import { NavItemGroup } from '@/types';
// import { Link, usePage } from '@inertiajs/react';

import { ChevronLeft } from 'lucide-react';

import { Collapsible, CollapsibleContent, CollapsibleTrigger } from '@/components/ui/collapsible';
import {
    SidebarGroup,
    SidebarGroupLabel,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
    SidebarMenuSub,
    SidebarMenuSubButton,
    SidebarMenuSubItem,
} from '@/components/ui/sidebar';
import { Link, usePage } from '@inertiajs/react';
import { cn } from '@/lib/utils';

export function NavMain({ items }: { items: NavItemGroup[] }) {
    const a = usePage()
    console.log(a.url,route().current());
    return (
        <SidebarGroup>
            <SidebarGroupLabel>Platform</SidebarGroupLabel>
            <SidebarMenu>
                {items.map((item) => (
                    <Collapsible key={item.title} asChild defaultOpen={route().current()?.includes(item.href)} className="group/collapsible">
                        <SidebarMenuItem>
                            <CollapsibleTrigger asChild>
                                <SidebarMenuButton tooltip={item.title}>
                                    {item.icon && <item.icon />}
                                    <span>{item.title}</span>
                                    <ChevronLeft className="ms-auto transition-transform duration-200 group-data-[state=open]/collapsible:rotate-90" />
                                </SidebarMenuButton>
                            </CollapsibleTrigger>
                            <CollapsibleContent >
                                <SidebarMenuSub>
                                    {item.items?.map((subItem) => (
                                        <SidebarMenuSubItem key={subItem.title}  className={''} >
                                            <SidebarMenuSubButton asChild>
                                                <Link href={route(subItem.href)}   >
                                                    <span className={cn(route().current()===subItem.href?'text-blue-600 text-[16px]':"")}>{subItem.title}</span>
                                                </Link>
                                            </SidebarMenuSubButton>
                                        </SidebarMenuSubItem>
                                    ))}
                                </SidebarMenuSub>
                            </CollapsibleContent>
                        </SidebarMenuItem>
                    </Collapsible>
                ))}
            </SidebarMenu>
        </SidebarGroup>
    );
}

// export function NavMain({ items = [] }: { items: NavItemGroup[] }) {
//     const page = usePage();
//     return (
//         <SidebarGroup className="px-2 py-0">
//             <SidebarGroupLabel>Platform</SidebarGroupLabel>
//             <SidebarMenu>
//                 {items.map((item) => (
//                     <SidebarMenuItem key={item.title}>
//                         <SidebarMenuButton asChild isActive={page.url.startsWith(item.href)} tooltip={{ children: item.title }}>
//                             <Link href={item.href} prefetch>
//                                 {item.icon && <item.icon />}
//                                 <span>{item.title}</span>
//                             </Link>
//                         </SidebarMenuButton>
//                     </SidebarMenuItem>
//                 ))}
//             </SidebarMenu>
//         </SidebarGroup>
//     );
// }
