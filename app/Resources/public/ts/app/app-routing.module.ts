import {NgModule} from "@angular/core";
import {RouterModule} from "@angular/router";
import {InquiryComponent} from "./inquiry/component/inquiry.component";

@NgModule({
    imports: [
        RouterModule.forRoot([
            {path: '', component: InquiryComponent},
        ])
    ],
    exports: [RouterModule]
})
export class AppRoutingModule {}
