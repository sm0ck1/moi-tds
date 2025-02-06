import Pusher from 'pusher-js';
import * as PusherTypes from 'pusher-js';

interface IPusherService {
    subscribe(channel: PusherChannels, event: PusherEvents, callback: (data: any) => void): void;
    unsubscribe(channel: PusherChannels, event?: PusherEvents): void;
    disconnect(): void;
}

export enum PusherChannels {
    visitUser = 'visit-user',
}

export enum PusherEvents {
    newVisit = 'new-visit',
}

export class PusherService implements IPusherService {
    private pusher: Pusher;
    private channels: Map<string, PusherTypes.Channel>;

    constructor() {
        const key = import.meta.env.VITE_PUSHER_APP_KEY ?? '';
        const cluster = import.meta.env.VITE_PUSHER_APP_CLUSTER ?? '';

        if (!key || !cluster) {
            throw new Error('Pusher key and cluster must be provided');
        }

        this.pusher = new Pusher(key, {
            cluster: cluster,

            enabledTransports: ['ws', 'wss']
        });
        this.channels = new Map();
    }

    subscribe(channel: PusherChannels, event: PusherEvents, callback: (data: any) => void) {
        let pusherChannel = this.channels.get(channel);
        if (!pusherChannel) {
            pusherChannel = this.pusher.subscribe(channel);
            this.channels.set(channel, pusherChannel);
        }
        pusherChannel.bind(event, callback);
    }

    unsubscribe(channel: PusherChannels, event?: PusherEvents) {
        const pusherChannel = this.channels.get(channel);
        if (pusherChannel) {
            if (event) {
                pusherChannel.unbind(event);
            } else {
                pusherChannel.unbind_all();
                this.pusher.unsubscribe(channel);
                this.channels.delete(channel);
            }
        }
    }

    disconnect() {
        this.channels.forEach((channel, channelName) => {
            channel.unbind_all();
            this.pusher.unsubscribe(channelName);
        });
        this.channels.clear();
        this.pusher.disconnect();
    }
}
