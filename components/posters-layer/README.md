# 海报生成组件

## 组件使用

```html
<posters-layer
    paintingData="{{postersData}}"
    bind:success="onSuccessCreatePosters"
    bind:error="onPostersError">
</posters-layer>
<script>
import postersLayer from '../../components/posters-layer/index';
export default {
    data() {
        postersData: { views: [] }
    },
    components: {
        postersLayer
    },
    onLoad() {
        this.createPosters();
    },
    methods: {
        createPosters() {
            const config = {
                width: 1000,
                height: 1000,
                background: '#ffffff',
                radius: 0,
                views: [
                    {
                        type: 'image',
                        round: true,
                        width: 100,
                        height: 100,
                        left: 325,
                        top: 38,
                        url: ''
                    }
                ]
            };
            this.postersData = config;
        },
        onSuccessCreatePosters(res) {},
        onPostersError() {}
    }
}
</script>
```


## 组件参数解释

### config字段


| 字段             | 类型                     | 必填 | 描述                                       |
| --------------- | ------------------------ | ---- | ------------------------------------------ |
| width           | Number(单位:px)         | 是   | 画布宽度                                   |
| height          | Number(单位:px)         | 是   | 画布高度                                   |
| background      | String                 | 否   | 画布背景颜色                                   |
| radius          | Number                  | 否   | 圆角 |
| views           | Array                  | 否   | 海报的所有元素             |

### views字段

#### 文本
| 字段             | 类型                     | 必填 | 描述 |
| --------------- | ------------------------ | ---- | ------------------------------------------ |
| type            | String                   | 是   | 类型，值：text                               | 
| width           | Number(单位:px)           | 是   | 宽度                                   | 
| height          | Number(单位:px)           | 否   | 高度                                   | 
| left            | Number(单位:px)           | 否   | 距离海报左边距                          |
| top             | Number(单位:px)           | 否   | 距离海报上边距                          |
| fontSize        | Number(单位:px)           | 否   | 字体大小，默认：16                       |
| lineHeight      | Number(单位:px)           | 否   | 行高，默认：20                           |
| breakWord       | Boolean                  | 否   | 是否自动换行，默认：false                   |
| bolder          | Boolean                  | 否   | 是否加粗，默认：false                      |
| textAlign       | String                   | 否   | 对齐方式，可选值：left、center、right，默认：left    |
| color           | String                   | 否   | 字体颜色                                  |
| content         | String                   | 是   | 文本内容                                 |
| MaxLineNumber   | Number                   | 否   | 显示多少行，超出省略                        |


### 矩形
| 字段             | 类型                     | 必填 | 描述 |
| --------------- | ------------------------ | ---- | ------------------------------------------ |
| type            | String                   | 是   | 类型，值：rect                              | 
| width           | Number(单位:px)           | 是   | 宽度                                       | 
| height          | Number(单位:px)           | 是   | 高度                                       | 
| left            | Number(单位:px)           | 否   | 距离海报左边距                               | 
| top             | Number(单位:px)           | 否   | 距离海报上边距                               | 
| radius          | Number(单位:px)           | 否   | 圆角半径，如果radius === width / 2，则是个圆，和CSS一样   | 
| background      | String                   | 否   | 填充背景色                                   | 

### 图片
| 字段             | 类型                     | 必填 | 描述 |
| --------------- | ------------------------ | ---- | ------------------------------------------ |
| type            | String                   | 是   | 类型，值：image                              | 
| tailor          | Number(单位:px)           | 否   | 裁剪方式，可选值：center                           | 
| radius          | Number(单位:px)           | 否   | 圆角半径，如果radius === width / 2，则是个圆，和CSS一样   | 
| width           | Number(单位:px)           | 是   | 宽度                               | 
| height          | Number(单位:px)           | 是   | 高度                               | 
| left            | Number(单位:px)           | 否   | 距离海报左边距                               | 
| top             | Number(单位:px)           | 否   | 距离海报上边距                               | 
| url             | String                   | 是   | 图片路径                               | 


## 事件

### `success` 海报生成成功时触发

### `error` 海报生成失败时触发